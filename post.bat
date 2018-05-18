@echo off
curl http://localhost:8000/api/v1/p/my-channel ^
 -X POST ^
 -H "Content-Type: application/json" ^
 -H "Accept: application/json, application/xml, */*" ^
 -H "Content-Type: application/json" ^
 -H "Authorization: Bearer 96262b26-28f4-4448-962b-d9fd10b18344" ^
 -d @test.json
