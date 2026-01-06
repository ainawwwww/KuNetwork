<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-container { max-width: 800px; margin: auto; background: #fff; padding: 20px; }
        .invoice-header img, .invoice-footer img { width: 100%; height: auto; }
        .table th, .table td { text-align: center; }
        .table th, .table td {
    padding: 5px !important; /* Reduce padding */
    font-size: 14px; /* Adjust font size */
    line-height: 1; /* Reduce line height */
    vertical-align: middle; /* Align content to middle */
}
    </style>
</head>
<body>
    <button class="btn btn-primary mt-3 w-100" onclick="downloadPDF()">Download PDF</button>
<div class="invoice-container" id="invoice">
        <div class="invoice-header text-center">
            <img src="images/invoice_header.jpg" alt="Invoice Header">
        </div>
        
        <div class="row mt-1">
            <div class="col-7 border border-2 border-black" style="line-height: 1;">
                <h6>INVOICE TO:ergherheh</h6>
                <p>Company Name: Free zone establishment - FZE</p>
                <p>Location:wengorngorgghe</p>
                <p>Number: +971 55 713 9620</p>
            </div>
            <div class="col-5 border border-2 border-black p-2" style="line-height: 1;">
                <p><strong>DATE:</strong> 11/26/2024</p>
                <p><strong>INVOICE #:</strong> <span class="text-danger">rn/24/20A52</span></p>
                <p><strong>SALES:</strong> R.N Printing</p>
                <p><strong>TERMS:</strong> R.N Printing</p>
            </div>
        </div>

        <table class="table table-bordered border-black table-striped" style="border-bottom: 5px solid black;">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>DESCRIPTION</th>
                    <th>QTY</th>
                    <th>U. PRICE</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Paper bag 31x43x12cm (one color printing)</td><td>70 pcs</td><td>850 AED</td><td>850 AED</td></tr>
                <tr><td>2</td><td>Corrugated box 40x30x12cm (One side one color printing)</td><td>70 pcs</td><td>650 AED</td><td>650 AED</td></tr>
                <tr><td>3</td><td>Card A5 350gsm Matt Lamination</td><td>70 pcs</td><td>400 AED</td><td>400 AED</td></tr>
                <tr><td>1</td><td>Paper bag 31x43x12cm (one color printing)</td><td>70 pcs</td><td>850 AED</td><td>850 AED</td></tr>
                <tr><td>2</td><td>Corrugated box 40x30x12cm (One side one color printing)</td><td>70 pcs</td><td>650 AED</td><td>650 AED</td></tr>
                <tr><td>3</td><td>Card A5 350gsm Matt Lamination</td><td>70 pcs</td><td>400 AED</td><td>400 AED</td></tr>
                <tr><td>1</td><td>Paper bag 31x43x12cm (one color printing)</td><td>70 pcs</td><td>850 AED</td><td>850 AED</td></tr>
                <tr><td>2</td><td>Corrugated box 40x30x12cm (One side one color printing)</td><td>70 pcs</td><td>650 AED</td><td>650 AED</td></tr>
                <tr><td>3</td><td>Card A5 350gsm Matt Lamination</td><td>70 pcs</td><td>400 AED</td><td>400 AED</td></tr>
                <tr><td>2</td><td>Corrugated box 40x30x12cm (One side one color printing)</td><td>70 pcs</td><td>650 AED</td><td>650 AED</td></tr>
                <tr><td>3</td><td>Card A5 350gsm Matt Lamination</td><td>70 pcs</td><td>400 AED</td><td>400 AED</td></tr>
                <tr><td>1</td><td>Paper bag 31x43x12cm (one color printing)</td><td>70 pcs</td><td>850 AED</td><td>850 AED</td></tr>
                <tr><td>2</td><td>Corrugated box 40x30x12cm (One side one color printing)</td><td>70 pcs</td><td>650 AED</td><td>650 AED</td></tr>
                <tr><td>3</td><td>Card A5 350gsm Matt Lamination</td><td>70 pcs</td><td>400 AED</td><td>400 AED</td></tr>
            </tbody>
        </table>
        <div class="row ">
            <div class="col-8 text-center">
            <p><strong>THANK YOU FOR YOUR BUSINESS..!</strong></p>
            </div>
            <div class="col-4">
            <div class="text-end" style="margin-top: -15px; "> 
                <table class="table table-bordered table-striped border-black border-2" style="border: 5px solid black;">
                    <tr>
                        <td class="table-dark"><strong>TOTAL:</strong></td>
                        <td>1900 AED</td>
                    </tr>
                    <tr>
                        <td class="table-dark"><strong>Paid:</strong></td>
                        <td>1000 AED</td>
                    </tr>
                    <tr>
                        <td class="table-dark"><strong>Balance:</strong></td>
                        <td>900 AED</td>
                    </tr>
                </table>
            <!-- <p><strong>TOTAL:</strong> 1900 AED</p>
            <p><strong>Paid:</strong> 1000 AED</p>
            <p><strong>Balance:</strong> 900 AED</p> -->
        </div>
            </div>

        </div>
        
       

        <div class="mt-1">
            <h6><b>TERMS & CONDITIONS</b></h6>
            <ol>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
            </ol>
            
        </div>

        <div class="mt-1 d-flex justify-content-between">
            <div>
                <h6>Receiver</h6>
                <p>___________________</p>
            </div>
            <div>
                <h6>Signature</h6>
                <p>___________________</p>
            </div>
        </div>

        <div class="invoice-footer mt-3">
            <img src="images/invoice_footer.jpg" alt="Invoice Footer" class="w-100">
        </div>

        
    </div>
    
    <script>
        function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: 'p', // Portrait mode
        unit: 'mm',
        format: 'a4'
    });

    const invoice = document.querySelector("#invoice");

    html2canvas(invoice, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL("image/png");
        const imgWidth = 190; // Width for the image inside the PDF
        const pageHeight = 297; // A4 Page height in mm
        const imgHeight = (canvas.height * imgWidth) / canvas.width; // Maintain aspect ratio

        let position = 10;

        doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);

        let remainingHeight = imgHeight;
        while (remainingHeight > pageHeight - 20) {
            position = position - pageHeight + 20;
            doc.addPage();
            doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            remainingHeight -= pageHeight - 20;
        }

        doc.save("invoice.pdf");
    });
}

    </script>
</body>
</html>

