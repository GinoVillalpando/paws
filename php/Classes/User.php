<?php

	namespace PawsForCause\Paws;

	require_once ("autoload.php");
	require_once (dirname(__DIR__)) . "/vendor/autoload.php";

   use http\Exception\InvalidArgumentException;
   use Ramsey\Uuid\Uuid;

	/**
    * User Class
    *
    * This is the user class for the Capstone Project - Paws for the Cause.
    *
    * @author Matthew Urrea <matt.urrea.code@gmail.com>
    *
    **/

class User{
   /**
    * id for the user; this is the primary key
    * @var Uuid $userId
    **/
   private $userId;
   /**
    * id for userActivationToken
    * @var Uuid $userActivationToken
    **/
   private $userActivationToken;
   /**
    * integer for user age
    * @var integer $userAge;
    **/
   private $userAge;
   /**
    * email of the user
    * @var string $userEmail;
    **/
   private $userEmail;
   /**
    * first name of the user
    * @var string $userFirstName;
    **/
   private $userFirstName;
   /**
    * id for user password
    * @var string $userHash;
    **/
   private $userHash;
   /**
    * last name of user
    * @var string $userLastName;
    **/
   private $userLastName;
   /**
    *  phone number of user
    * @var string $userPhone;
    **/
   private $userPhone;

   /**
    * constructor method for user
    * @param $userId id for the user
    * @param $userActiviationToken activationtoken for the author
    * @param $userAge age of user
    * @param $userEmail user email
    * @param $userFirstName user first name
    * @param $userHash password for the user
    * @param $userLastName user last name
    * @param $userPhone user phone number
    *
    **/
   public function __construct($userId, $userActivationToken, $userAge, $userDescription, $userEmail, $userFirstName, $userHash, $userLastName, $userPhone) {
      $this->userId = $userId;
      $this->userActivationToken = $userActivationToken;
      $this->userAge = $userAge;
      $this->userDescription = $userDescription;
      $this->userEmail = $userEmail;
      $this->userFirstName = $userFirstName;
      $this->userHash = $userHash;
      $this->userLastName = $userLastName;
      $this->userPhone = $userPhone;
   }

   /**
    * accessor method for user id
    *
    * @return Uuid value of user id
    **/
   public function getUserId(): Uuid {
      return ($this->userId);
   }

   /**
    * mutator method for user id
    *
    * @param Uuid|string $newUserId new value of user id
    * @throws \RangeException if $newUserId is not positive
    * @throws \TypeError if $newUserId is not a uuid or string
    **/
   public function setUserId($newUserId): void {
      try {
         $uuid = self::validateUuid($newUserId);
      } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
         $exceptionType = get_class($exception);
         throw (new $exceptionType($exception->getMessage(), 0, $exception));
      }

      //convert and store author id
      $this->userId = $uuid;
   }

   /**
    * accessor method for user activation token
    *
    * @return Uuid value of activation token
    **/
   public function getUserActivationToken(): ?string {
      return ($this->getUserActivationToken);
   }

   /**
    * mutator method for author activation token
    *
    * @param string $newUserActivationToken
    * @throws \InvalidArgumentException if the token is not a string or insecure
    * @throws \RangeException if $newUserActivationToken is not exactly 32 characters
    * @throws \TypeError if $newUserActivationToken is not a string
    **/
   public function setUserActivationToken(?string $newUserActivationToken) : void {
      if($newUserActivationToken === null){
         $this->userActivationToken = null;
         return;
      }
      $newUserActivationToken = strolower(trim($newUserActivationToken));
      if(ctype_xdigit($newUserActivationToken) === false) {
         throw(new\RangeException("user activation token is not valid"));
      }
      $this->userActivationToken = $newUserActivationToken;
   }

   /**
    * accessor method for user age
    *
    * @return integer value of the user description
    **/
   public function getUserAge() : int {
      return ($this->userAge);
   }

   /**
    * mutator method for user age
    *
    * @param integer $newUserAge new value of the users age
    * @throws \InvalidArgumentException if $newUserAge is not a integer or insecure
    * @throws \RangeException if $newUserAge is < 120
    * @throws \TypeError if $newUserAge is not a integer
    **/
   public function setUserAge(int $newUserAge) : void {
      //verify the age integer is secure
      $newUserAge = trim($newUserAge);
      $newUserAge = filter_var($newUserAge, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_NO_ENCODE_QUOTES);
      if(empty($newUserAge)===true){
         throw(new \InvalidArgumentException("Age value empty or insecure"));
      }
      //verify age
      if(is_integer($newUserAge) > 120){
         throw (new \RangeException("Age value too large"));
      }
      //store the age value
      $this->userAge= $newUserAge;
   }

   /**
    * accessor method for user email
    *
    * @return string value of user email
    **/
    public function getUserEmail() : string {
       return($this->userEmail);
    }

   /**
    * mutator method for user email
    *
    * @param string $newUserEmail
    * @throws \InvalidArgumentException if the email is not a string or insecure
    * @throws \RangeException if $newUserEmail is not over 64 characters
    * @throws \TypeError if $newUserEmail is not a string
    **/
   public function setUserEmail(string $newUserEmail) : void {
      //verify that the user email is secure
      $newUserEmail = trim($newUserEmail);
      $newUserEmail = filter_var($newUserEmail, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
      if(empty($newUserEmail)===true) {
         throw(new \InvalidArgumentException("User email is empty or insecure"));
      }
         //verify that the user email will fit in the database
         if(strlen($newUserEmail) > 64) {
            throw(new \RangeException("User email is too long"));
         }

         //store the user email
         $this->userEmail = $newUserEmail;
      }

   /**
    * accessor method for user first name
    *
    * @return string value of user first name
    **/
    public function getUserFirstName() : string {
       return ($this->userFirstName);
    }

   /**
    * mutator method for user first name
    *
    * @param string $newUserFirstName new value for user first name
    * @throws \InvalidArgumentException if $newUserFirstName is not a string or insecure
    * @throws \RangeException if $newUserFirstName is > 32 characters
    * @throws \TypeError if $newUserFirstName is not a string
    **/
   public function setUserFirstName(string $newUserFirstName) : void {
      //verify that the user first name is secure
      $newUserFirstName = trim($newUserFirstName);
      $newUserFirstName = filter_var($newUserFirstName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
      if(empty($newUserFirstName)===true) {
         throw(new \InvalidArgumentException("User first name is empty or insecure"));
      }
      //verify that the user first name will fit in the database
      if(strlen($newUserFirstName) > 32) {
         throw(new \RangeException("User first name is too long"));
      }

      //store the user email
      $this->userFirstName = $newUserFirstName;
   }

    /**
    * accessor method for user hash
    *
    * @return string value of user hash
    **/
    public function getUserHash() : string {
       return ($this->userHash);
    }

   /**
    * mutator method for user password
    *
    * @param string $newUserHash string containing encrypted password
    * @throws \InvalidArgumentException if the hash is not secure
    * @throws \RangeException if $newUserHash is not 97 characters
    * @throws \TypeError if $newUserHash is not a sting
    **/
   public function setUserHash(string $newUserHash) : void {
      //enforce that the hash is properly formatted
      $newUserHash = trim($newUserHash);
      if(empty($newUserHash) === true) {
         throw(new \InvalidArgumentException("user password hash empty or insecure"));
      }
      //enforce that the hash is exactly 97 characters
      if(strlen($newUserHash) < 96) {
         throw(new \RangeException("user hash must be less than 96 characters"));
      }
      //store the hash
      $this->userHash = $newUserHash;
   }

   /**
    * accessor method for user last name
    *
    * @return string for user last name
    **/
    public function getUserLastName() : string {
      return $this->userLastName;
    }

   /**
    * mutator method for user last name
    *
    * @param string $newUserLastName new value for user first name
    * @throws \InvalidArgumentException if $newUserLastName is not a string or insecure
    * @throws \RangeException if $newUserLastName is > 32 characters
    * @throws \TypeError if $newUserLastName is not a string
    **/
   public function setUserLastName(string $newUserLastName) : void {
      //verify that the user last name is secure
      $newUserLastName = trim($newUserLastName);
      $newUserLastName = filter_var($newUserLastName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
      if(empty($newUserLastName)===true) {
         throw(new \InvalidArgumentException("User last name is empty or insecure"));
      }
      //verify that the user last name will fit in the database
      if(strlen($newUserLastName) > 32) {
         throw(new \RangeException("User last name is too long"));
      }

      //store the user last name
      $this->userLastName = $newUserLastName;
   }

   /**
    * accessor method for user phone number
    *
    * @return string value of user phone number
    **/
   public function getUserPhone(): string {
      return $this->userPhone;
   }

   /**
    * mutator method for user phone number
    *
    * @param string $newUserPhone new value of user phone number
    * @throws \InvalidArgumentException if $newUserPhone is not a string or insecure
    * @throws \RangeException if $newUserPhone is > 11 characters
    * @throws \TypeError if $newUserPhone is not a string
    **/
   public function setUserPhone(string $newUserPhone) : void {
      //verify the phone number is a string and secure
      $newUserPhone = trim($newUserPhone);
      $newUserPhone = filter_var($newUserPhone, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
      if(empty($newUserPhone) === true) {
         throw(new \InvalidArgumentException("user phone number is empty or insecure"));
      }

      //verify the phone number will fit in the database
      if(strlen($newUserPhone) > 11){
         throw(new \RangeException("user phone number is too long"));
      }

      //store phone number
      $this->userPhone = $newUserPhone;
   }

   /**
    * inserts user into mySQL
    *
    * @param \PDO $pdo PDO connection object
    * @throws \PDOException when mySQL related errors occur
    * @throws \TypeError if $pdo is not a PDO connection object
    **/
   public function insert(\PDO $pdo) : void {

      //create query template
      $query = "INSERT INTO  user(userId, userActivationToken, userAge, userEmail, userFirstName, userHash, userLastName, userPhone) VALUES (:userId, :userActivationToken, :userAge, :userEmail, :userFirstName, :userHash, :userLastName, :userPhoneNumber)";
      $statement = $pdo->prepare($query);

      //bind the member variables to the place holders in the template
      $parameters = ["userId" => $this->userId->getBytes(), "userActivationToken" => $this->userActivationToken->getBytes(), "userAge" => $this->userAge, "userEmail" => $this->userEmail, "userFirstName" => $this->userFirstName, "userHash" => $this->userHash, "userLastName" => $this->userLastName, "userPhone" => $this->userPhone];
      $statement->execute($parameters);
   }

   /**
    * deletes user from mySQL
    *
    * @param \PDO $pdo PDO connection object
    * @throws \PDOException when mySQL related errors occur
    * @throws \TypeError if $pdo is not a PDO connection object
    **/
   public function delete(\PDO $pdo) : void {
      //create query template
      $query = "DELETE FROM user WHERE userId = :userId";
      $statement = $pdo->prepare($query);

      //bind the member variable to the place holder in the template
      $parameters = ["userId" => $this->userId->getBytes()];
      $statement->execute($parameters);
   }

   /**
    * updates the user in mySQL
    *
    * @param \PDO $pdo PDO connection object
    * @throws \PDOException when mySQL related errors occur
    * @throws \TypeError if $pdo is not a PDO connection object
    **/
   public function update(\PDO $pdo) : void {
      //create query template
      $query = "UPDATE user SET userId = :userId, userActivationToken = :userActivationToken, userAge = :userAge, userEmail = :userEmail, userFirstName = :userFirstName, userHash = :userHash, userLastName = :userLastName, userPhone = :userPhone";
      $statement = $pdo->prepare($query);

      $parameters = ["userId" => $this->userId->getBytes(), "userActivationToken" => $this->userActivationToken->getBytes(), "userAge" => $this->userAge, "userEmail" => $this->userEmail, "userFirstName" => $this->userFirstName, "userHash" => $this->userHash, "userLastName" => $this->userLastName, "userPhone" => $this->userPhone];
   $statement->execute($parameters);
   }

   /**
    * gets user by userId
    *
    * @param \PDO $pdo PDO connection object
    * @param Uuid|string $userId user id to search for
    * @return User|null user found or null if not found
    * @throws \PDOException when my SQL related errors occur
    * @throws \TypeError when a variable are not the correct data type
    **/
   public static function getUserByUserId(\PDO $pdo, $userId) : ?User {
      //sanitize the userId before searching
      try{
         $userId = self::validateUuid($userId);
      } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception){
         throw(new \PDOException($exception->getMessage(), 0, $exception));
      }

      //create query template
      $query = "SELECT userId, userActivationToken, userAge, userEmail, userFirstName, userHash, userLastName, userPhone";
      $statement = $pdo->prepare($query);

      //bind the user id to the place holder in the template
      $parameters = ["userId" => $userId->getBytes()];
      $statement->execute($parameters);

      //grab user from mySQL
      try{
         $user = null;
         $statement->setFetchMode(\PDO::FETCH_ASSOC);
         $row = $statement->fetch();
         if($row !== false) {
            $user = new User($row["userId"], $row["userActivationToken"], $row["userAge"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userPhone"]);
         }
      } catch(\Exception $exception){
         //if the row couldn't be converted, rethrow it
         throw(new \PDOException($exception->getMessage(), 0, $exception));
      }
      return($user);
   }

   /**
    * gets user by userEmail
    *
    * @param \PDO $pdo PDO connection object
    * @param Uuid|string $userEmail email value to search for
    * @return User|null User found or null if not found
    * @throws \PDOException when mySQL related errors occur
    * @throws \TypeError when a variable are not the correct data type
    */
   public static function getUserByEmail(\PDO $pdo, $userEmail, $exception) : ?User {
      //sanitize the userEmail before searching
      try{
         $userEmail = self::string($userEmail);
      }catch( \InvalidArgumentException | \RangeException | \Exception |
   \TypeError $exception) {
         throw(new \PDOException($exception->getMessage(), 0, $exception));
      }

      //create query template
      $query = "SELECT userId, userActivationToken, userAge, userEmail, userFirstName, user, userHash, userLastName, userPhone";
      $statement = $pdo->prepare($query);

      //bind the user email to the place holder in the template
      $parameters = ["userEmail" => $userEmail->getBytes()];
      $statement->execute($parameters);

      //grab user from mySQL
      try{
         $user = null;
         $statement->setFetchMode(\PDO::FETCH_ASSOC);
         $row = $statement->fetch();
         if($row !== false) {
            $user = new User($row["userId"], $row["userActivationToken"], $row["userAge"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userPhone"]);
         }
      } catch(\Exception $exception){
         //if the row couldn't be converted, rethrow it
         throw(new \PDOException($exception->getMessage(), 0, $exception));
      }
      return($user);
   }

   /**
    * get user by activation token
    *
    * @param \PDO $pdo PDO connection object
    * @param Uuid|string $userActivationToken activation token value to search for
    * @return User|null User found or null if not found
    * @throws \PDOException when mySQL related errors occur
    * @throws \TypeError when a variable are not the correct data type
    **/
   public static function getUserByActivationToken(\PDO $pdo, $userActivationToken) : ?User {
      //sanitize the userActivationToken before searching
      try {
         $userActivationToken = self::ValidateUuid($userActivationToken);
      } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
         throw(new\PDOException($exception->getMessage(), 0, $exception));
      }

      //create query template
      $query = "SELECT userId, userActivationToken, userAge, userEmail, userFirstName, userHash, userLastName, userPhone";
      $statement = $pdo->prepare($query);

      //bind the user activation token to the place holder in the template
      $parameters = ["userActivationToken" => $userActivationToken->getBytes()];
      $statement->execute($parameters);

      //grab user from mySQL
      try {
         $user = null;
         $statement->setFetchMode(\PDO::FETCH_ASSOC);
         $row = $statement->fetch();
         if($row !== false) {
            $user = new User($row["userId"], $row["userActivationToken"], $row["userAge"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userPhone"]);
         }
      } catch(\Exception $exception) {
         //if the row couldn't be converted, rethrow it
         throw(new \PDOException($exception->getMessage(), 0, $exception));
      }
      return ($user);
   }

}