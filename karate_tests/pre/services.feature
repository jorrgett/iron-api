Feature: Basic services test

Background:
    * def loginResponse = call read('login.feature')
    * def token = loginResponse.token
    * def authHeader = 'Bearer ' + token
    * def baseURL = 'https://iron-test-api.maxcodex.com/api'

Scenario: Check the response on search services
    Given url baseURL + '/search_services'
    And header Authorization = authHeader
    When method get
    Then status 200

Scenario: Check the response on search services
    Given url baseURL + '/service_balancing'
    And header Authorization = authHeader
    When method get
    Then status 200

Scenario: Check the response on search services
    Given url baseURL + '/service_inspections'
    And params ({user_id: 92913, service_id: 45559})
    And header Authorization = authHeader
    When method get
    Then status 200


Scenario: Check the response on search services
    Given url baseURL + '/service_oil'
    And header Authorization = authHeader
    When method get
    Then status 200