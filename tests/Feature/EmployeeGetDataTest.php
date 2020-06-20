<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EmployeeGetDataTest extends TestCase
{
    use DatabaseTransactions;
    
    // This only work once you have seeded the database
    public function testGetEmployeeDataSortAscByName()
    {
        $minSalary = 0;
        $maxSalary = 10000000;
        $offset = 0;
        $limit = 30;
        $sort = "%2Bname"; // url encoded + is %2B

        // Example params: /users?minSalary=0&maxSalary=4000&offset=0&limit=30&sort=+name
        $params = 'minSalary='.$minSalary;
        $params = $params.'&maxSalary='.$maxSalary;
        $params = $params.'&offset='.$offset;
        $params = $params.'&limit='.$limit;
        $params = $params.'&sort='.$sort;

        $response = $this->get('/users?'.$params);
        
        $response->assertStatus(200);
        $response->assertJsonCount(30, $key = null);

        // Check if it is alphabetical
        $isAlphabetical = true;
        $currAlpha = "";
        foreach($response->getData() as $item){
            if(!$currAlpha){
                $currAlpha = ($item->name)[0];
            }
            if (($item->name)[0] < $currAlpha){
                $isAlphabetical = false;
                break;
            }
        }
        $this->assertTrue($isAlphabetical);
    }

    public function testGetEmployeeDataSortDescBySalary()
    {
        $minSalary = 0;
        $maxSalary = 10000000;
        $offset = 0;
        $limit = 30;
        $sort = "-salary";

        $params = 'minSalary='.$minSalary;
        $params = $params.'&maxSalary='.$maxSalary;
        $params = $params.'&offset='.$offset;
        $params = $params.'&limit='.$limit;
        $params = $params.'&sort='.$sort;

        $response = $this->get('/users?'.$params);
        
        $response->assertStatus(200);
        $response->assertJsonCount(30, $key = null);

        $hasLesserSalary = true;
        $currSal = "";
        foreach($response->getData() as $item){
            if(!$currSal){
                $currSal = ($item->salary);
            }
            if (($item->salary) > $currSal){
                $hasLesserSalary = false;
                break;
            }
        }
        $this->assertTrue($hasLesserSalary);
    }

    public function testGetEmployeeDataWithOffset()
    {
        $minSalary = 0;
        $maxSalary = 10000000;
        $offset = 40;
        $limit = 30;
        $sort = "-salary"; 

        $params = 'minSalary='.$minSalary;
        $params = $params.'&maxSalary='.$maxSalary;
        $params = $params.'&offset='.$offset;
        $params = $params.'&limit='.$limit;
        $params = $params.'&sort='.$sort;

        $response = $this->get('/users?'.$params);
        
        $response->assertStatus(200);
        $response->assertJsonCount(10, $key = null);

        $hasLesserSalary = true;
        $currSal = "";
        foreach($response->getData() as $item){
            if(!$currSal){
                $currSal = ($item->salary);
            }
            if (($item->salary) > $currSal){
                $hasLesserSalary = false;
                break;
            }
        }
        $this->assertTrue($hasLesserSalary);
    }
}
