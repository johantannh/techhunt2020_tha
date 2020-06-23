<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EmployeeCRUDTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testCreateEmployee()
    {
        $expectedId = "test4567";
        $expectedLogin = "testuser4567";
        $expectedName = "Test User 2";
        $expectedSalary = 999999.99;

        $expectedCreateJson = [
            'id' => $expectedId,
            'login' => $expectedLogin,
            'name' => $expectedName,
            'salary' => $expectedSalary,
        ];

        $response = $this->json('POST', '/users/'.$expectedId, $expectedCreateJson);

        $this->assertDatabaseHas('employees', $expectedCreateJson);

        $response->assertStatus(200);
    }

    public function testInvalidCreateEmployee()
    {
        $expectedId = "test4567";
        $expectedLogin = "testuser4567";
        $expectedName = "Test User 2";
        $expectedSalary = -1234;

        $expectedCreateJson = [
            'id' => $expectedId,
            'login' => $expectedLogin,
            'name' => $expectedName,
            'salary' => $expectedSalary,
        ];

        $response = $this->json('POST', '/users/'.$expectedId, $expectedCreateJson);

        $this->assertDatabaseMissing('employees', $expectedCreateJson);

        $response->assertStatus(400);
    }

    public function testGetEmployee()
    {
        $employeeId = "test1234";
        $response = $this->get('/users/'.$employeeId);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                "id" => "test1234",
                "login" => "testuser1234",
                "name" => "Test User",
                "salary" => 12999.99,
            ]);
    }

    public function testInvalidGetEmployee()
    {
        $employeeId = "test12345";
        $response = $this->get('/users/'.$employeeId);
        $response->assertStatus(400);
    }

    public function testUpdateEmployee()
    {
        $employeeId = "test1234";

        $expectedId = "test4567";
        $expectedLogin = "testuser4567";
        $expectedName = "Test User 2";
        $expectedSalary = 999999.99;

        $expectedUpdateJson = [
            'edit-id' => $expectedId,
            'login' => $expectedLogin,
            'name' => $expectedName,
            'salary' => $expectedSalary,
        ];

        $expectedDbEntry = [
            'id' => $expectedId,
            'login' => $expectedLogin,
            'name' => $expectedName,
            'salary' => $expectedSalary,
        ];

        $response = $this->json('PATCH', '/users/'.$employeeId, $expectedUpdateJson);
        
        $this->assertDatabaseHas('employees', $expectedDbEntry);

        $response->assertStatus(200);
    }

    public function testInvalidUpdateEmployee()
    {
        $employeeId = "test1234";

        $expectedId = "test4567";
        $expectedLogin = "testuser4567";
        $expectedName = "Test User 2";
        $expectedSalary = -123;

        $expectedUpdateJson = [
            'edit-id' => $expectedId,
            'login' => $expectedLogin,
            'name' => $expectedName,
            'salary' => $expectedSalary,
        ];

        $expectedDbEntry = [
            "id" => "test1234",
            "login" => "testuser1234",
            "name" => "Test User",
            "salary" => 12999.99,
        ];

        $response = $this->json('PATCH', '/users/'.$employeeId, $expectedUpdateJson);
        
        $this->assertDatabaseHas('employees', $expectedDbEntry);

        $response->assertStatus(400);
    }

    public function testDeleteEmployee()
    {
        $employeeId = "test1234";

        $expectedDbEntry = [
            "id" => "test1234",
            "login" => "testuser1234",
            "name" => "Test User",
            "salary" => 12999.99,
        ];

        $response = $this->json('delete', '/users/'.$employeeId);
        
        $this->assertDatabaseMissing('employees', $expectedDbEntry);

        $response->assertStatus(200);
    }

    public function testInvalidDeleteEmployee()
    {
        $employeeId = "test12345";

        $expectedDbEntry = [
            "id" => "test1234",
            "login" => "testuser1234",
            "name" => "Test User",
            "salary" => 12999.99,
        ];

        $response = $this->json('delete', '/users/'.$employeeId);
        
        // Make sure the entry still exists
        $this->assertDatabaseHas('employees', $expectedDbEntry);

        $response->assertStatus(400);
    }
}
