Feature: Get vehicles testing

Background:
    * def loginResponse = call read('login.feature')
    * def token = loginResponse.token
    * def authHeader = 'Bearer ' + token
    * def baseURL = 'https://iron-test-api.maxcodex.com/api'

Scenario: Check the response on venicles
    Given url baseURL + '/vehicles'
    And header Authorization = authHeader
    When method get
    Then status 200


Scenario Outline: Check the response on vehicles
    Given url baseURL + '/vehicles?size=<size>'
    And header Authorization = authHeader
    When method get
    Then status 200

Examples:
    | size |
    | 30   |

#Scenario Outline: Check the response on vehicles with specific res_partner_id
#    Given url baseURL + '/vehicles?res_partner_id=<res_partner_id>'
#    And header Authorization = authHeader
#    When method get
#    Then status 200
#
#Examples:
#    | res_partner_id |
#    | 97407          |
#    | 92913          |


#Scenario: Check the response on vehicles brands models
#    Given url baseURL + '/vehicle_brands/models'
#    And header Authorization = authHeader
#    When method get
#    Then status 200

Scenario Outline: Check the response on tire_histories
    Given url baseURL + '/tire_histories?vehicle_id=<vehicle_id>'
    And header Authorization = authHeader
    When method get
    Then status 200

Examples:
    | vehicle_id |
    | 65909      |
