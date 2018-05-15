@echo off
curl http://localhost:8000/chamois:spa ^
 -X POST ^
 -H "Content-Type: application/json" ^
 -H "Accept: application/json, application/xml, */*" ^
 -H "Content-Type: application/json" ^
 -H "Authorization: Bearer chamoispalol" ^
 -d @test.json
