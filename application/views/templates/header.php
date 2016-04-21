<html>
<head>
  <title>
    <?php
    if (isset($title)) {
      echo $title;
    }else{
      echo 'Kitmaker';
    }
    ?>
  </title>
<!-- js -->
  <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
  <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
<!-- css -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
</head>
<body>
  <div id="content">
<!-- header -->
    <div id="header">
      <h1>
        <a href="<?php echo site_url(''); ?>">
          <div id="logo">
            <img src="<?php echo base_url(); ?>assets/img/kitmaker_logo.png" alt="logo">
          </div>
        </a>
      </h1>
    </div>
