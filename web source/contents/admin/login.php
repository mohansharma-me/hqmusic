<div class="window">
    <div class="header">
        <div class="text-head">
            <a href="/admin">
                <img src="/theme/images/users.png" />
                <h1>Authentication</h1>
            </a>
        </div>
    </div>
    <div class="content">

        <form method="post" action="/admin" class="form1 half-width center">
            <fieldset>
                <label>Username:</label><br/>
                <input type="text" name="username" placeholder="enter your username" value="<?=$myData["username"]?>" />
            </fieldset>
            <fieldset>
                <label>Password:</label><br/>
                <input type="password" name="password" placeholder="enter your password" />
            </fieldset>
            <fieldset>
                <input type="submit" class="lefty" value="Authenticate Now" />
                <label class="righty"><?=$myData["errorMessage"]?></label>
            </fieldset>
        </form>
        <br/><br/>
    </div>
</div>