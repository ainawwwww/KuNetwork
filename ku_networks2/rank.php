<?php
// rank.php
// Backend endpoints for Rank progress and Claiming
// Usage:
//  GET  rank.php?action=get_status
//  POST rank.php?action=claim_rank   (POST param: rank_name)

require 'config.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
    
}

$uid = (int) $_SESSION['user_id'];

// small helper: check column exists
function column_exists(mysqli $conn, string $table, string $column): bool {
    // ✅ Escape identifiers properly to avoid SQL injection
    $table = $conn->real_escape_string($table);
    $column = $conn->real_escape_string($column);

    $sql = "SHOW COLUMNS FROM `$table` LIKE '$column'";
    $result = $conn->query($sql);
    $exists = ($result && $result->num_rows > 0);
    return $exists;
}


// helper to fetch teamX (directs) user ids
function get_team_x_ids(mysqli $conn, int $user_id): array {
    $ids = [];
    $sql = "SELECT DISTINCT referral_userid FROM referal_teams WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $ids[] = (int)$r['referral_userid'];
        }
        $stmt->close();
    }
    return array_values(array_filter($ids));
}

// compute teamX initial sum (first payment per team member) efficiently
function team_x_initial_sum(mysqli $conn, array $teamIds): float {
    if (empty($teamIds)) return 0.0;
    // sanitize IDs to integers and build inline list (safe for numeric IDs)
    $ids = array_map('intval', $teamIds);
    $idsList = implode(',', $ids);
    if ($idsList === '') return 0.0;

    // We select the earliest payment (MIN(id)) per user, then sum their amounts
    $sql = "
    SELECT SUM(p.amount) AS total_sum
    FROM payment p
    JOIN (
      SELECT user_id, MIN(id) AS min_id
      FROM payment
      WHERE user_id IN ($idsList)
      GROUP BY user_id
    ) m ON p.user_id = m.user_id AND p.id = m.min_id
    ";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return 0.0;
    $total = 0.0;
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $total = (float)($row['total_sum'] ?? 0.0);
        }
    }
    $stmt->close();
    return $total;
}

// Fetch user wallet total_balance (used as Self-Invest)
function get_user_total_balance(mysqli $conn, int $user_id): float {
    $sql = "SELECT total_balance, balance FROM user_wallets WHERE user_id = ? LIMIT 1";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $stmt->bind_result($total_balance, $balance);
            if ($stmt->fetch()) {
                $stmt->close();
                if ($total_balance !== null) return (float)$total_balance;
                return (float)$balance;
            }
        }
        $stmt->close();
    }
    return 0.0;
}

// Fetch assigned rank name (if any)
function get_assigned_rank(mysqli $conn, int $user_id): ?string {
    $sql = "SELECT assigned_rank FROM rank_assignment_summary WHERE user_id = ? LIMIT 1";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $stmt->close();
                return $row['assigned_rank'] ?? null;
            }
        }
        $stmt->close();
    }
    return null;
}

// Load rank definitions
function load_ranks(mysqli $conn): array {
    $out = [];
    $sql = "SELECT id, rank_name, self_invest, team_business, self_bonus, event_bonus FROM rank_bonuses ORDER BY id ASC";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) {
            $out[] = [
                'id' => (int)$r['id'],
                'name' => $r['rank_name'],
                'self_invest' => (float)$r['self_invest'],
                'team_business' => (float)$r['team_business'],
                'self_bonus' => (float)$r['self_bonus'],
                'event_bonus' => (float)$r['event_bonus'],
            ];
        }
        $stmt->close();
    }
    return $out;
}

$action = $_REQUEST['action'] ?? 'get_status';

try {
    if ($action === 'get_status') {
        // compute data
        $ranks = load_ranks($conn);
        $assignedRank = get_assigned_rank($conn, $uid);
        // compute team x ids and initial sum
        $teamXIds = get_team_x_ids($conn, $uid);
        $teamXSum = team_x_initial_sum($conn, $teamXIds);
        $selfInvest = get_user_total_balance($conn, $uid);

        // find current rank index
        $curIndex = -1;
        if (!empty($assignedRank)) {
            foreach ($ranks as $i => $r) {
                if (strcasecmp(trim($r['name']), trim($assignedRank)) === 0) { $curIndex = $i; break; }
            }
        }
        $nextRank = null;
        if ($curIndex === -1) {
            $nextRank = (!empty($ranks)) ? $ranks[0] : null;
        } else {
            $nextRank = $ranks[$curIndex+1] ?? null;
        }

        $investmentPct = 0;
        $teamPct = 0;
        $overallPct = 0;
        $eligible_for_next = false;
        if ($nextRank) {
            $reqSelf = max(0.0, (float)$nextRank['self_invest']);
            $reqTeam = max(0.0, (float)$nextRank['team_business']);
            if ($reqSelf > 0) $investmentPct = (int) min(100, round(($selfInvest / $reqSelf) * 100));
            else $investmentPct = ($selfInvest > 0) ? 100 : 0;
            if ($reqTeam > 0) $teamPct = (int) min(100, round(($teamXSum / $reqTeam) * 100));
            else $teamPct = ($teamXSum > 0) ? 100 : 0;
            $overallPct = (int) min(100, round((($investmentPct + $teamPct) / 2)));
            $eligible_for_next = ($investmentPct >= 100 && $teamPct >= 100);
        }

        $data = [
            'current_rank' => $assignedRank ?: 'No Rank',
            'next_rank' => $nextRank ? $nextRank['name'] : null,
            'self_invest' => $selfInvest,
            'team_x_initial_sum' => $teamXSum,
            'investmentPct' => $investmentPct,
            'teamPct' => $teamPct,
            'overallPct' => $overallPct,
            'eligible' => $eligible_for_next,
            'next_self_bonus' => $nextRank ? (float)$nextRank['self_bonus'] : 0.0,
            'next_event_bonus' => $nextRank ? (float)$nextRank['event_bonus'] : 0.0,
        ];

        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }

    if ($action === 'claim_rank') {
        // Claiming must be POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        $postedRankName = trim((string)($_POST['rank_name'] ?? ''));

        if ($postedRankName === '') {
            echo json_encode(['success' => false, 'message' => 'Rank name required']);
            exit;
        }

        // load ranks and find the row
        $ranks = load_ranks($conn);
        $rankToClaim = null;
        foreach ($ranks as $r) {
            if (strcasecmp($r['name'], $postedRankName) === 0) { $rankToClaim = $r; break; }
        }
        if (!$rankToClaim) {
            echo json_encode(['success' => false, 'message' => 'Invalid rank selected']);
            exit;
        }

        // recompute current metrics server-side
        $teamXIds = get_team_x_ids($conn, $uid);
        $teamXSum = team_x_initial_sum($conn, $teamXIds);
        $selfInvest = get_user_total_balance($conn, $uid);

        $reqSelf = max(0.0, (float)$rankToClaim['self_invest']);
        $reqTeam = max(0.0, (float)$rankToClaim['team_business']);
        $server_invest_ok = ($reqSelf <= 0) ? true : ($selfInvest >= $reqSelf);
        $server_team_ok = ($reqTeam <= 0) ? true : ($teamXSum >= $reqTeam);

        if (!($server_invest_ok && $server_team_ok)) {
            echo json_encode(['success' => false, 'message' => 'You do not meet the requirements for ' . htmlspecialchars($rankToClaim['name'])]);
            exit;
        }

        // All good — perform transaction: credit wallet, insert bonus_history, update/insert rank_assignment_summary
        $creditAmount = (float)$rankToClaim['self_bonus'];
        $walletCurrency = 'USD';
        $bhHasMeta = column_exists($conn, 'bonus_history', 'meta');

        try {
            $conn->begin_transaction();

            // 1) update wallet (try update first)
            $sqlUpdateWallet = "UPDATE user_wallets SET total_balance = COALESCE(total_balance,0) + ?, available_balance = COALESCE(available_balance,0) + ? WHERE user_id = ?";
            $stmtW = $conn->prepare($sqlUpdateWallet);
            if (!$stmtW) throw new Exception("Prepare wallet update failed: " . $conn->error);
            $stmtW->bind_param("ddi", $creditAmount, $creditAmount, $uid);
            $stmtW->execute();
            $affected = $stmtW->affected_rows;
            $stmtW->close();

            if ($affected === 0) {
                // insert
                $sqlInsertWallet = "INSERT INTO user_wallets (user_id, balance, total_balance, available_balance, currency) VALUES (?, 0, ?, ?, ?)";
                $stmtIW = $conn->prepare($sqlInsertWallet);
                if (!$stmtIW) throw new Exception("Prepare wallet insert failed: " . $conn->error);
                $stmtIW->bind_param("idds", $uid, $creditAmount, $creditAmount, $walletCurrency);
                $stmtIW->execute();
                $stmtIW->close();
            }

            // 2) insert into bonus_history
            $metaPayload = json_encode([
                'rank_claimed' => $rankToClaim['name'],
                'self_invest' => $selfInvest,
                'team_business_initial' => $teamXSum
            ]);
            if ($bhHasMeta) {
                $sqlBH = "INSERT INTO bonus_history (user_id, bonus_amount, bonus_type, created_at, meta) VALUES (?, ?, 'rank_self_bonus', UTC_TIMESTAMP(), ?)";
                $stmtBH = $conn->prepare($sqlBH);
                $stmtBH->bind_param("ids", $uid, $creditAmount, $metaPayload);
                $stmtBH->execute();
                $stmtBH->close();
            } else {
                $sqlBH2 = "INSERT INTO bonus_history (user_id, bonus_amount, bonus_type, created_at) VALUES (?, ?, 'rank_self_bonus', UTC_TIMESTAMP())";
                $stmtBH2 = $conn->prepare($sqlBH2);
                $stmtBH2->bind_param("id", $uid, $creditAmount);
                $stmtBH2->execute();
                $stmtBH2->close();
            }

            // 3) update or insert rank_assignment_summary
            $exists = false;
            $stmtCheck = $conn->prepare("SELECT id FROM rank_assignment_summary WHERE user_id = ? LIMIT 1");
            if ($stmtCheck) {
                $stmtCheck->bind_param("i", $uid);
                $stmtCheck->execute();
                $stmtCheck->store_result();
                if ($stmtCheck->num_rows > 0) $exists = true;
                $stmtCheck->close();
            }

            if ($exists) {
                $sqlUpdateRank = "UPDATE rank_assignment_summary SET assigned_rank = ?, self_invest = ?, team_business = ?, user_available_balance = ?, calculation_timestamp = UTC_TIMESTAMP() WHERE user_id = ?";
                $stmtUR = $conn->prepare($sqlUpdateRank);
                if (!$stmtUR) throw new Exception("Prepare rank update failed");
                // fetch user name for safety
                $safeName = '';
                $stmtName = $conn->prepare("SELECT name FROM users WHERE id = ? LIMIT 1");
                if ($stmtName) { $stmtName->bind_param("i", $uid); $stmtName->execute(); $stmtName->bind_result($safeNameTmp); if ($stmtName->fetch()) $safeName = $safeNameTmp; $stmtName->close(); }
                $stmtUR->bind_param("sdddi", $rankToClaim['name'], $selfInvest, $teamXSum, $selfInvest, $uid);
                $stmtUR->execute();
                $stmtUR->close();
            } else {
                $sqlInsertRank = "INSERT INTO rank_assignment_summary (user_id, user_name, assigned_rank, self_invest, team_business, user_available_balance, calculation_timestamp) VALUES (?, ?, ?, ?, ?, ?, UTC_TIMESTAMP())";
                $stmtIR = $conn->prepare($sqlInsertRank);
                if (!$stmtIR) throw new Exception("Prepare rank insert failed");
                // get user name
                $safeName = '';
                $stmtName = $conn->prepare("SELECT name FROM users WHERE id = ? LIMIT 1");
                if ($stmtName) { $stmtName->bind_param("i", $uid); $stmtName->execute(); $stmtName->bind_result($safeNameTmp); if ($stmtName->fetch()) $safeName = $safeNameTmp; $stmtName->close(); }
                $stmtIR->bind_param("issdds", $uid, $safeName, $rankToClaim['name'], $selfInvest, $teamXSum, $selfInvest);
                $stmtIR->execute();
                $stmtIR->close();
            }

            $conn->commit();

            // return updated wallet value to caller
            $newBalance = get_user_total_balance($conn, $uid);

            echo json_encode([
                'success' => true,
                'message' => "Successfully credited " . number_format($creditAmount,2) . " to your wallet as Self Bonus for " . htmlspecialchars($rankToClaim['name']),
                'new_balance' => $newBalance,
                'new_rank' => $rankToClaim['name']
            ]);
            exit;
        } catch (Exception $ex) {
            if ($conn->errno) $conn->rollback();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to process claim: ' . $ex->getMessage()]);
            exit;
        }
    }

    // unknown action
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Unknown action']);
    exit;

} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $t->getMessage()]);
    exit;
}