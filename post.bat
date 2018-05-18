@echo off
curl http://localhost:8000/api/v1/p/my-channel ^
 -X POST ^
 -H "Content-Type: application/json" ^
 -H "Accept: application/json, application/xml, */*" ^
 -H "Content-Type: application/json" ^
 -H "Authorization: Bearer 117b48e8-eed5-44af-b0f9-8e2091362f1d" ^
 -d @test.json
