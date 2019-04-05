@if(Auth::check())
    <div class="block-button-log">
        <a class="button" href="/home">Профіль</a>
        <a class="button" href="/logout">Вийти</a>
    </div>

@else
    <div class="block-button-log">
        <a class="button" href="/login">Увійти</a>
        <a class="button" href="/register">Зареєструватись</a>

    </div>
@endif