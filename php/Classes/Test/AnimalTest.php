<?php

namespace PawsForCause\Paws\Test;

use PawsForCause\Paws\{Animal, Shelter};

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\QueryDataSet;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\Operation\{Composite, Factory, Operation};

// grab the encrypted properties file
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once(dirname(__DIR__) . "/autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

/**
 * Full PHPUnit test for the Animal class
 *
 * This is the Unit Test for the animal class for the Capstone Project
 *
 * @see Animal
 * @author Thomas Dameron <tdameron1@cnm.edu>
 */
class AnimalTest extends PawsTest {
	/**
	 * animalId that is in the database; this is for foreign key relations
	 * @var Animal animalId
	 */
	protected $animal = null;

	/**
	 * valid Animal Id for the animal
	 * @var $VALID_ANIMAL_ID
	 */
	protected $VALID_ANIMAL_ID;

	/**
	 * valid animal Shelter Id that is related to the animal
	 * @var $VALID_ANIMAL_SHELTER_ID ;
	 **/
	protected $VALID_ANIMAL_SHELTER_ID;

	/**
	 * Animal Adoption Status, whether or not the animal has been adopted
	 * @var string $VALID_ANIMAL_ADOPTION_STATUS
	 **/
	protected $VALID_ANIMAL_ADOPTION_STATUS = "PHPUnit test passing";

	/**
	 * The breed of the animal
	 * @var string $ANIMAL_BREED
	 **/
	protected $VALID_ANIMAL_BREED = "PHPUnit test passing";

	/**
	 * The gender of the animal
	 * @var string $VALID_ANIMAL_GENDER
	 **/
	protected $VALID_ANIMAL_GENDER = "PHPUnit test passing";

	/**
	 * The name of the animal
	 * @var string $VALID_ANIMAL_NAME
	 **/
	protected $VALID_ANIMAL_NAME = "PHPUnit test passing";

	/**
	 * The Photo URL of the anima
	 * @var string $VALID_ANIMAL_PHOTO_URL ;
	 **/
	protected $VALID_ANIMAL_PHOTO_URL = "PHPUnit test passing";

	/**
	 * The animal species
	 * @var string $VALID_ANIMAL_SPECIES ;
	 **/
	protected $VALID_ANIMAL_SPECIES = "PHPUnit test passing";


	 /** create dependant objects before running each test **/

	  public final function setUp() : void{
	  // run the default setUp method first
	  parent::setUp();

	   // create and insert an animal to assign status's to.
	  $this->animal = new Animal(generateUuidV4(), generateUuidV4(), "Whatever", "Corgi", 1, "Olaf", "https://media.giphy.com", "Dog");
	  $this->animal->insert($this->getPDO());

//	  $this->shelter = new Shelter(generateUuidV4(), "444 Fakelane 83729", "Magic Mountain", "555-555-5555");
//	  $this->shelter->insert($this->getPDO());
	  }


	/**
	 * test inserting an Animal and verify that the actual mySQL data matches
	 **/
	public function testInsertValidAnimal(): void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("animal");

		//create a new animal and insert it into mySQL
		$animalId = generateUuidv4();
		$animal = new Animal($animalId, $this->shelter->getShelterId(), $this->VALID_ANIMAL_ADOPTION_STATUS, $this->VALID_ANIMAL_BREED, $this->VALID_ANIMAL_GENDER, $this->VALID_ANIMAL_NAME, $this->VALID_ANIMAL_PHOTO_URL, $this->VALID_ANIMAL_SPECIES);
		$animal->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoAnimal = Animal::getAnimalByAnimalId($this->getPDO(), $animal->getAnimalid());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("animal"));
		$this->assertEquals($pdoAnimal->getAnimalId(), $animalId);
		$this->assertEquals($pdoAnimal->getAnimalShelterId(), $this->shelter->getShelterId());
		$this->assertEquals($pdoAnimal->getAnimalAdoptionStatus(), $this->VALID_ANIMAL_ADOPTION_STATUS);
		$this->assertEquals($pdoAnimal->getAnimalBreed(), $this->VALID_ANIMAL_BREED);
		$this->assertEquals($pdoAnimal->getAnimalGender(), $this->VALID_ANIMAL_GENDER);
		$this->assertEquals($pdoAnimal->getAnimalName(), $this->VALID_ANIMAL_NAME);
		$this->assertEquals($pdoAnimal->getAnimalPhotoUrl(), $this->VALID_ANIMAL_PHOTO_URL);
		$this->assertEquals($pdoAnimal->getAnimalSpecies(), $this->VALID_ANIMAL_SPECIES);
	}

	/**
	 * test inserting an animal, editing it, and then updating it
	 */
	public function testUpdateValidAnimal(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("animal");

		// create a new animal and insert it into my SQL
		$animalId = generateUuidv4();
		$animal = new Animal($this->animal->getAnimalId(), $this->VALID_ANIMAL_SHELTER_ID, $this->VALID_ANIMAL_ADOPTION_STATUS, $this->VALID_ANIMAL_BREED, $this->VALID_ANIMAL_GENDER, $this->VALID_ANIMAL_NAME, $this->VALID_ANIMAL_PHOTO_URL, $this->VALID_ANIMAL_SPECIES);
		$animal->insert($this->getPDO());

		//edit the animal and update it in mySQL
		$animal = setAnimalAdoptionStatus($this->VALID_ANIMAL_ADOPTION_STATUS);
		$animal = setAnimalBreed($this->VALID_ANIMAL_BREED);
		$animal->setAnimalGender($this->VALID_ANIMAL_GENDER);
		$animal->setAnimalName($this->VALID_ANIMAL_NAME);
		$animal->setAnimalPhotoUrl($this->VALID_ANIMAL_PHOTO_URL);
		$animal->setAnimalSpecies($this->VALID_ANIMAL_SPECIES);
	}

	/**
	 * A test for putting a new animal and then deleting it
	 */
	public function testDeleteValidAnimal(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("animal");

		// create a new animal and insert it into my SQL
		$animalId = generateUuidv4();
		$animal = new Animal($this->animal->getAnimalId(), $this->VALID_ANIMAL_SHELTER_ID, $this->VALID_ANIMAL_ADOPTION_STATUS, $this->VALID_ANIMAL_BREED, $this->VALID_ANIMAL_GENDER, $this->VALID_ANIMAL_NAME, $this->VALID_ANIMAL_PHOTO_URL, $this->VALID_ANIMAL_SPECIES);
		$animal->insert($this->getPDO());

		//delete the animal from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("animal"));
		$animal->delete($this->getPDO());

		//gran the data from mySQL and enforce the Animal does not exist
		$pdoAnimal = Animal::getAnimalByAnimalId($this->getPDO(), $animal->getAnimalId());
		$this->assertNull($pdoAnimal);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("animal"));
	}

	public function testGetValidAnimalByAnimalId() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("animal");

		// create a new Animal and insert into mySQL
		$animalId = generateUuidV4();
		$animal = new Animal($animalId, $this->VALID_ANIMAL_SHELTER_ID, $this->VALID_ANIMAL_ADOPTION_STATUS, $this->VALID_ANIMAL_BREED, $this->VALID_ANIMAL_GENDER, $this->VALID_ANIMAL_NAME, $this->VALID_ANIMAL_PHOTO_URL, $this->VALID_ANIMAL_SPECIES);
		$animal->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$results = Animal::getAnimalByAnimalId($this->getPDO(), $animal->getAnimalId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("animal"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("PawsForCause\Paws\Animal", $results);


		//grab the result from the array and validate it
		$pdoAnimal = $results[0];

		$this->assertEquals($pdoAnimal->getAnimalId(), $animalId);
		$this->assertEquals($pdoAnimal->getAnimalId(), $this->animal->getAnimalId());
		$this->assertEquals($pdoAnimal->getAnimalAdoptionStatus(), $this->VALID_ANIMAL_ADOPTION_STATUS);
		$this->assertEquals($pdoAnimal->getAnimalBreed(), $this->VALID_ANIMAL_BREED);
		$this->assertEquals($pdoAnimal->getAnimalGender(), $this->VALID_ANIMAL_GENDER);
		$this->assertEquals($pdoAnimal->getAnimalName(), $this->VALID_ANIMAL_NAME);
		$this->assertEquals($pdoAnimal->getAnimalPhotoUrl(), $this->VALID_ANIMAL_PHOTO_URL);
		$this->assertEquals($pdoAnimal->getAnimalSpecies(), $this->VALID_ANIMAL_SPECIES);
	}

	/**
	 * Test grabbing animal by Shelter Id
	 **/
	public function testGetValidAnimalByShelterId(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("animal");

		// create a new animal and insert to mySQL
		$animalId = generateUuidV4();
		$animal = new Animal($animalId, $this->shelter->getShelterId(), $this->animal->getAnimalId(), $this->VALID_ANIMAL_SHELTER_ID, $this->VALID_ANIMAL_ADOPTION_STATUS, $this->VALID_ANIMAL_BREED, $this->VALID_ANIMAL_GENDER, $this->VALID_ANIMAL_NAME, $this->VALID_ANIMAL_PHOTO_URL, $this->VALID_ANIMAL_SPECIES);
		$animal->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Animal::getAnimalByShelterId($this->getPDO(), $animal->getAnimalShelterId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("animal"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("PawsForCause\Paws\Animal", $results);

		// grab the result from the array and validate it
		$pdoAnimal = $results[0];

		$this->assertEquals($pdoAnimal->getAnimalId(), $animalId);
		$this->assertEquals($pdoAnimal->getAnimalId(), $this->shelter->getAnimalShelterId());
		$this->assertEquals($pdoAnimal->getAnimalAdoptionStatus(), $this->VALID_ANIMAL_ADOPTION_STATUS);
		$this->assertEquals($pdoAnimal->getAnimalBreed(), $this->VALID_ANIMAL_BREED);
		$this->assertEquals($pdoAnimal->getAnimalGender(), $this->VALID_ANIMAL_GENDER);
		$this->assertEquals($pdoAnimal->getAnimalName(), $this->VALID_ANIMAL_NAME);
		$this->assertEquals($pdoAnimal->getAnimalPhotoUrl(), $this->VALID_ANIMAL_PHOTO_URL);
		$this->assertEquals($pdoAnimal->getAnimalSpecies(), $this->VALID_ANIMAL_SPECIES);
	}

	/**
	 * Test grabbing animal by Shelter Id
	 **/
	public function testGetValidAnimalByLikeUserId(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("animal");

		// create a new animal and insert to mySQL
		$animalId = generateUuidV4();
		$animal = new Animal($animalId, $this->like->likeUserId(), $this->animal->getAnimalId(), $this->VALID_ANIMAL_SHELTER_ID, $this->VALID_ANIMAL_ADOPTION_STATUS, $this->VALID_ANIMAL_BREED, $this->VALID_ANIMAL_GENDER, $this->VALID_ANIMAL_NAME, $this->VALID_ANIMAL_PHOTO_URL, $this->VALID_ANIMAL_SPECIES);
		$animal->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Animal::getAnimalBylikeUserId($this->getPDO(), $animal->getAnimalShelterId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("animal"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("PawsForCause\Paws\Animal", $results);

		// grab the result from the array and validate it
		$pdoAnimal = $results[0];

		$this->assertEquals($pdoAnimal->getAnimalId(), $animalId);
		$this->assertEquals($pdoAnimal->getAnimalId(), $this->shelter->getAnimalShelterId());
		$this->assertEquals($pdoAnimal->getAnimalAdoptionStatus(), $this->VALID_ANIMAL_ADOPTION_STATUS);
		$this->assertEquals($pdoAnimal->getAnimalBreed(), $this->VALID_ANIMAL_BREED);
		$this->assertEquals($pdoAnimal->getAnimalGender(), $this->VALID_ANIMAL_GENDER);
		$this->assertEquals($pdoAnimal->getAnimalName(), $this->VALID_ANIMAL_NAME);
		$this->assertEquals($pdoAnimal->getAnimalPhotoUrl(), $this->VALID_ANIMAL_PHOTO_URL);
		$this->assertEquals($pdoAnimal->getAnimalSpecies(), $this->VALID_ANIMAL_SPECIES);
	}
}