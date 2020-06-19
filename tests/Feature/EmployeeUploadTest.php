<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EmployeeUploadTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testValidEmployeeUpload()
    {
        $header = 'id,login,name,salary';
        $row1 = 'e0001,fakeuser,Faker,99.99';
        $row2 = 'e0002,fakeuser2,Faker,99.99';

        $content = implode("\n", [$header, $row1, $row2]);
        $file = UploadedFile::fake()->createWithContent('test.csv', $content, 'text/csv');
        $response = $this->json('POST', '/users/upload', [
            'file' => $file,
        ]);
        
        $row1Split = explode(',', $row1);
        $this->assertDatabaseHas('employees', [
                'id'        =>  $row1Split[0],
                "login"     =>  $row1Split[1],
                "name"      =>  $row1Split[2],
                "salary"    =>  $row1Split[3]
        ]);

        $row2Split = explode(',', $row2);
        $this->assertDatabaseHas('employees', [
            'id'        =>  $row2Split[0],
            "login"     =>  $row2Split[1],
            "name"      =>  $row2Split[2],
            "salary"    =>  $row2Split[3]
        ]);

        $response->assertStatus(200);
    }

    public function testDuplicateEmployeeUpload()
    {
        $header = 'id,login,name,salary';
        $row1 = 'e0001,fakeuser,Faker,99.99';
        $row2 = 'e0001,fakeuser,Faker,99.99';

        $content = implode("\n", [$header, $row1, $row2]);
        $file = UploadedFile::fake()->createWithContent('test.csv', $content, 'text/csv');
        $response = $this->json('POST', '/users/upload', [
            'file' => $file,
        ]);

        $row1Split = explode(',', $row1);
        $this->assertDatabaseMissing('employees', [
            'id'        =>  $row1Split[0],
            "login"     =>  $row1Split[1],
            "name"      =>  $row1Split[2],
            "salary"    =>  $row1Split[3]
        ]);

        $response->assertStatus(400);
    }

    public function testInvalidColumnsForEmployeeUpload()
    {
        $header = 'id,login,name,salary';
        $row1 = 'e0001,fakeuser,';
        $row2 = 'e0001,fakeuser,Faker,99.99';

        $content = implode("\n", [$header, $row1, $row2]);
        $file = UploadedFile::fake()->createWithContent('test.csv', $content, 'text/csv');
        $response = $this->json('POST', '/users/upload', [
            'file' => $file,
        ]);
        
        $row2Split = explode(',', $row2);
        $this->assertDatabaseMissing('employees', [
            'id'        =>  $row2Split[0],
            "login"     =>  $row2Split[1],
            "name"      =>  $row2Split[2],
            "salary"    =>  $row2Split[3]
        ]);

        $response->assertStatus(400);
    }
}
