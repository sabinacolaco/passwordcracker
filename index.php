<?php
session_start();
$_SESSION['token'] = md5(uniqid(mt_rand(), true));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Password Cracker</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <div class="header">
            <h3>Password Cracker</h3>
        </div>
        <div class="container pt-5">
            <h2>Building a Password Cracker</h2>            
            <div class="col-md-8 offset-md-2 pt-3">
                <div id="error" style="display: none;"></div>
                <form class="form-inline" id="frmcrackit">
                    <div class="card">
                        <div class="card-header">Select the type of password and lets get cracking.</div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="optradio" value="Easy">&nbsp;<strong>Easy</strong><p>Lists the user IDs who have used numbers as their passwords i.e. 12345</p>
                                </label>
                            </li>
                            <li class="list-group-item">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="optradio" value="Medium1">&nbsp;<strong>Medium</strong><p>Lists the user IDs who have just used 3 Uppercase characters and 1 number as their password i.e. ABC1</p>
                                </label>
                            </li>
                            <li class="list-group-item">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="optradio" value="Medium2">&nbsp;<strong>Medium</strong><p>Lists the user IDs who have just used lowercase dictionary words (Max 6 chars) as their passwords i.e. london</p>
                                </label>
                            </li>
                            <li class="list-group-item">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="optradio" value="Hard">&nbsp;<strong>Hard</strong><p>Lists the user IDs who have used a 6 character passwords using a mix of Upper, Lowercase and numbers i.e AbC12z</p>
                                </label>
                            </li>
                        </ul>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                        <div class="card-body text-center">
                            <button type="submit" class="btn btn-primary mb-2">Crack it!</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="footer">
            <p class="text-muted">Copyright &copy; <?=date("Y");?> - All Rights Reserved.</p>
        </div>
        <!-- Spinner loader during ajax call-->
        <div id="loader" class="lds-dual-ring hidden overlay"></div>
        <!-- Modal to list the user IDs-->
        <div class="modal fade" id="userModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">User IDs</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script type="text/javascript" src="assets/js/mainscript.js"></script>
    </body>
</html>