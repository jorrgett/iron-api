Feature: Get privacy terms and conditions

Background:
    * def loginResponse = call read('login.feature')
    * def token = loginResponse.token
    * def authHeader = 'Bearer ' + token
    * def baseURL = 'https://iron-test-api.maxcodex.com/api'

Scenario: Check the response on privacy term and conditions
    Given url baseURL + '/privacy_terms_conditions'
    And header Authorization = authHeader
    When method get
    Then status 200

Scenario: Check the response on applications/lastest versions
    Given url baseURL + '/applications/latest-versions'
    And header Authorization = authHeader
    When method get
    Then status 200

Scenario: Check the response on applications/lastest versions
    Given url baseURL + '/store_services'
    And header Authorization = authHeader
    When method get
    Then status 200


