<h1>login!</h1>

<form action="{{ url(['for': "login"]) }}" method="post">
    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">email</label>
        <div class="col-md-6">
            <input id="email" type="email" class="form-control" name="email" value="" required autofocus>
        </div>
    </div>

    <div class="form-group row">
        <label for="password" class="col-md-4 col-form-label text-md-right">password</label>
        <div class="col-md-6">
            <input id="password" type="password" class="form-control" name="password" required>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6 offset-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    remember me
                </label>
            </div>
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn btn-primary">
                Login
            </button>
        </div>
    </div>
</form>