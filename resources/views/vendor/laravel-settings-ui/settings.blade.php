<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Laravel Settings UI</title>
</head>
<body>
<main role="main" class="container">
    <h1 class="mt-5">Laravel Settings UI</h1>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    {!! form_start($form) !!}

    {!! form_rest($form) !!}

    <div class="form-footer">
        <button type="submit" class="btn-success btn">{{ trans('laravel-settings-ui::settings.button.save') }}</button>
    </div>

    {!! form_end($form) !!}
</main>
</body>
</html>