<?php
session_start();
class User
{
    protected $_username;
    protected $_password;
    protected $_logged = false;
    protected $_email = '';

    public function __construct($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;
        $this->_logged = false;
    }

    public function setLogged($logged)
    {
        $this->_logged = $logged;
    }

    public function isLogged()
    {
        return $this->_logged;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function getPassword()
    {
        return $this->_password;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serialized_data = $_POST['data'];
    $data = unserialize($serialized_data);
}
$user = new User("admin","123");
$user->setLogged(true);
$serialized_data= serialize($user);
$data = str_replace(chr(0) . '*' . chr(0), '\0\0\0', $serialized_data);
$_SESSION['user'] = $data;
echo $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Unserialize Overflow Example</title>
</head>
<body>
<h1>PHP Unserialize Overflow Example</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="data">Serialized Data:</label>
    <textarea name="data" id="data" rows="10" cols="50"><?php if (isset($serialized_data)) echo htmlspecialchars($serialized_data); ?></textarea>
    <br>
    <input type="submit" value="Submit">
</form>
<?php if (isset($data)) { ?>
    <h2>Unserialized Data:</h2>
    <pre><?php print_r($data); ?></pre>
<?php } ?>
</body>
</html>
