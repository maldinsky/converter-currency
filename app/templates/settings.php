<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <base href="http://localhost:5050/">
    <title>Конвертор валют</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">Конвертер</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#header-main-navbar"
                    aria-controls="header-main-navbar" aria-expanded="false" aria-label="Меню">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="header-main-navbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/history">История</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/settings">Настройки</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="content">
    <div class="container">
        <form id="setting-form" action="/settings/save" method="post">
            <div class="form-group row my-4">
                <label class="col-md-6 col-form-label" for="history-limit">Количество записей в истории</label>
                <div class="col-md-6">
                    <input id="history-limit" class="form-control" type="text" name="history_limit" placeholder="Количество записей" value="<?= $history_limit; ?>">
                </div>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Название</th>
                    <th scope="col">Код</th>
                    <th scope="col">Скрыть</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($currencies as $currency) { ?>
                    <tr>
                        <td><?= $currency->getName(); ?></td>
                        <td><?= $currency->getCode(); ?></td>
                        <td>
                            <div class="form-check">
                                <input <?= (in_array($currency->getId(), $hide_currencies))? 'checked': ''; ?> class="form-check-input position-static" type="checkbox" name="hide_currencies[]" value="<?= $currency->getId(); ?>">
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <a href="javascript:void(0);" class="btn btn-primary btn-save-setting">Сохранить</a>
        </form>
    </div>
</main>
<footer>

</footer>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<script src="js/common.js"></script>
</body>
</html>
