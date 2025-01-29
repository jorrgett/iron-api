Feature: Login API

Scenario: Login and check the response
  Given url 'https://iron-test-api.maxcodex.com/api/login'
  And request {email: "admin@autobox.com", password: "Secret20a.", version: "1.0.0", platform: "android", platform_version: "9"}
  When method post
  Then status 200
  And match response.access_token != null
  And match response.user.permissions != []
  * def token = response.access_token
