<!DOCTYPE html>
<?php
require __DIR__ . "/vendor/autoload.php";
?>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>Atualização</title>

    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css"
          rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css"
          rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css"
          rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet"
          media="all">
    <meta resource=>
    <!-- Main CSS-->
    <link href="assets/css/main.css" rel="stylesheet" media="all">
    <style>
        :root {
            --fsphp: #1F2026;
            --fsline: #475163;
            --green: #35ba9b;
            --blue: #3aadd9;
            --yellow: #f5b945;
            --red: #d94452;
        }

        .trigger {
            font-size: 0.9em;
            padding: 15px;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
            border: 1px solid #cccccc;
        }

        .trigger small {
            display: block;
            margin-top: 7px;
            font-family: "Source Code Pro", serif;
            font-size: 0.75em;
        }

        .trigger.accept {
            color: var(--green);
            border-color: var(--green);
        }

        .trigger.warning {
            color: var(--yellow);
            border-color: var(--yellow);
        }

        .trigger.error {
            color: var(--red);
            border-color: var(--red);
        }
    </style>
</head>

<body>

<div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">
    <div class="wrapper wrapper--w680">
        <div class="card card-4">
            <div class="card-body">
                <h2 class="title" style="text-align: center">Formulário de
                    Atualização</h2>
                <?php
                $model = new \Source\Models\UserModel();
                $id = filter_input(INPUT_GET, "id");
                $user = $model->findById($id);
                $data = filter_input_array(INPUT_POST,
                    FILTER_SANITIZE_STRIPPED);

                $oldPhoto = "/assets/uploads/{$user->img}";
                if ($data) {
                    $fileUpload2 = $_FILES['photo'];

                    $allowedTypes = [
                        "image/jpg",
                        "image/jpeg",
                        "image/png",
                    ];

                    if ($_FILES && !empty($_FILES['photo']['name'])) {
                        $newFileName = time() . mb_strstr($fileUpload2['name'], ".");
                        if (in_array($fileUpload2['type'], $allowedTypes)) {
                            if (move_uploaded_file($fileUpload2['tmp_name'],
                                __DIR__ . "/assets/uploads/{$newFileName}")) {
                            }
                        } else {
                            echo "<p class='trigger warning'>Tipo de arquivo não permitido!</p>";
                        }

                        if ($user->img) {
                            unlink(__DIR__ . $oldPhoto);
                        }

                        $user->name = $data["name"];
                        $user->email = $data["email"];
                        $user->password = $data["password"];
                        $user->img = $newFileName;
                        $user->save();
                        if (!empty($user->fail())) {
                            echo "<p class='trigger error'>{$user->message()} <p/>";
                        } else {
                            echo "<p class='trigger accept'>{$user->message()} <p/>";
                        }
                    } else {
                        $user->name = $data["name"];
                        $user->email = $data["email"];
                        $user->password = $data["password"];
                        $user->save();
                        if (!empty($user->fail())) {
                            echo "<p class='trigger error'>{$user->message()} <p/>";
                        } else {
                            echo "<p class='trigger accept'>{$user->message()} <p/>";
                        }
                    }

                }

                ?>
                <form method="POST"
                      action="atualizar.php?id=<?= $id ?>"
                      enctype="multipart/form-data" novalidate>
                    <div class="row row-space">
                        <div class="col-2">
                            <div class="input-group">
                                <label class="label"> <i class="zmdi
                                zmdi-account-box" style="margin-right:
                                20px"></i>Nome</label>
                                <input class="input--style-4" style="width:
                                120%; border: 1px solid black;"
                                       type="text"
                                       name="name" value="<?= "$user->name" ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row row-space">
                        <div class="col-2">
                            <div class="input-group">
                                <label
                                        class="label"> <i class="zmdi
                                    zmdi-email" style="margin-right: 10px"
                                                          Email></i>Email</label>
                                <input class="input--style-4" style="width:
                                120%; border: 1px solid black;" type="email"
                                       name="email" value="<?= "$user->email"
                                ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row row-space">
                        <div class="col-2">
                            <div class="input-group">
                                <label class="label"> <i
                                            class="zmdi zmdi-lock"
                                            style="margin-right: 20px"
                                    ></i>Senha</label>
                                <input class="input--style-4" style="width:
                                120%; border: 1px solid black;" type="password"
                                       name="password"
                                       value="<?= "$user->password" ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row row-space">
                        <div class="col-2">
                            <div class="input-group">
                                <label class="label"> <i class="zmdi
                                zmdi-image" style="margin-right:
                                20px"></i>Foto</label>
                                <input class="input--style-4" type="file"
                                       name="photo">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="p-t-15">
                            <button class="btn btn--radius-2 btn--blue"
                                    type="submit">Atualizar
                            </button>
                        </div>
                        <div class="p-t-15" style="margin-left: 200px ">
                            <a
                                    class="btn btn--radius-2 btn--blue"
                                    href="listagem.php"
                                    style="text-decoration: none; color: white;
">Lista <i
                                        class="zmdi zmdi-arrow-right"
                                        style="margin-left: 10px"></i></a>
                        </div>
                    </div>
                </form>

                <?php

                ?>
            </div>
        </div>
    </div>
</div>

<!-- Jquery JS-->
<script src="vendor/jquery/jquery.min.js"></script>
<!-- Vendor JS-->
<script src="vendor/select2/select2.min.js"></script>
<script src="vendor/datepicker/moment.min.js"></script>
<script src="vendor/datepicker/daterangepicker.js"></script>

<!-- Main JS-->
<script src="assets/js/global.js"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->