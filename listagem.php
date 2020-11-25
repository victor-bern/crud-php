<?php
require __DIR__ . "/vendor/autoload.php";
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
          crossorigin="anonymous">
    
    <title>Listagem</title>
</head>
<body>
<?php
$model = new \Source\Models\UserModel();

$users = $model->all();

?>

<h1 style="text-align: center">Listagem de Usuários</h1>

<table class="table">
    <thead>
    <tr>
        <th scope="col">id</th>
        <th scope="col">Foto</th>
        <th scope="col">Nome</th>
        <th scope="col">email</th>
        <th scope="col">Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($users)) : ?>
    <?php foreach ($users as $user): ?>
        <tr>
            <th scope="row"><?= "{$user->id}"; ?></th>
            <?php if (empty($user->img)) : ?>
                <th><img style="border-radius: 50%; width: 50px;
                height: 50px" src="assets/uploads/defou.png"
                </th>
            <?php else: ?>
                <th><img style="border-radius: 50%; width: 50px;
                height: 50px" src="assets/uploads/<?= "{$user->img}"; ?>"
                </th>
            <?php endif; ?>

            <td><?= "{$user->name}"; ?></td>
            <td><?= "{$user->email}"; ?></td>
            <td>
                <button class="btn
                btn-outline-danger"><a style="text-decoration: none"
                                       href="listagem.php?id=<?= "{$user->id}";
                                       ?>">Deletar</a></button>
                <button class="btn btn-outline-warning"><a
                            style="text-decoration: none"
                            href="atualizar.php?id=<?= $user->id; ?>">Atualizar</a>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <?php else: ?>
</table>

    <h1 style="text-align: center">Sem usuários cadastrados</h1>
<?php endif; ?>

<?php
$id = filter_input(INPUT_GET, "id");
if ($id) {
    $user = $model->findById($id);
    if ($user) {
        $foto = "/assets/uploads/{$user->img}";
        if (file_exists($foto)) {
            unlink(__DIR__ . $foto);
        }
        $user->destroy();
        header("Location: /bdproject/listagem.php");
    }
}

?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
</body>
</html>
