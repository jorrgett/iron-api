Feature: Get vehicles testing

Background:
    * def loginResponse = call read('login.feature')
    * def token = loginResponse.token
    * def authHeader = 'Bearer ' + token
    * def baseURL = 'https://iron-test-api.maxcodex.com/api'

Scenario: Check the response on settings
    Given url baseURL + '/settings'
    And header Authorization = authHeader
    When method get
    Then status 200
    # And match response.warning_threshold != null
    # And match response.warning_color != null
    # And match response.danger_threshold != null


Scenario: Check the response on venicles
    Given url baseURL + '/store_services'
    And header Authorization = authHeader
    When method get
    Then status 200
