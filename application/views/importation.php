<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h2 style="text-decoration: underline;">Importation de données</h2>
        <form action="<?php echo site_url('CSV_Controller/process_etape') ?>" method="post" enctype="multipart/form-data">
            <h5>Etapes </h5>
            <input type="file" name="csv_file_etape" accept=".csv">
            <button type="submit">Importer</button>

        </form>

        <form action="<?php echo site_url('CSV_Controller/process_resultat') ?>" method="post" enctype="multipart/form-data">
            <h5>Resultats </h5>
            <input type="file" name="csv_file_result" accept=".csv">
            <button type="submit">Importer</button>

        </form>




    <form action="<?php echo site_url('CSV_Controller/process_points');?>" method="post" enctype="multipart/form-data">
        <h5>Points</h5>
        <input type="file" name="csv_file_points" accept=".csv">
        <button type="submit">Importer</button>
    </form>
</body>
</html>