Feature: Basic dashboard testing

Background:
    * def loginResponse = call read('login.feature')
    * def token = loginResponse.token
    * def authHeader = 'Bearer ' + token
    * def baseURL = 'https://iron-test-api.maxcodex.com/api'

Scenario Outline: Check the response on vehicles brand models
    Given url baseURL + '/vehicle_brands/models'
    And header Authorization = authHeader
    When method get
    Then status 200

Examples:
    | vehicle_id |
    | 65909      |
    | 62758      |

Scenario Outline: Check the response on dashboard_general
    Given url baseURL + '/dashboard_general/<vehicle_id>'
    And header Authorization = authHeader
    When method get
    Then status 200
    # Then match response.Dashboard.battery_data.battery_brand_name != null
    # And match response.Dashboard.battery_data.amperage != null
    # And match response.Dashboard.balancing_data.date_current != null
    # And match response.Dashboard.balancing_data.date_next != null
    # And match response.Dashboard.tire_data.date_current != null
    # And match response.Dashboard.tire_data.date_next != null

Examples:
    | vehicle_id |
    | 65909      |
    | 62758      |


Scenario Outline: # Check the response on battery_details
    Given url baseURL + '/battery_details/<vehicle_id>'
    And header Authorization = authHeader
    When method get
    Then status 200
    # Then match response.id != null
    # And match response.health_percentage != null
    # And match response.health_status != null

Examples:
    | vehicle_id |
    | 65909      |
    | 62758      |

Scenario Outline: Check the response on get_tire_chart
    Given url baseURL + '/get_tire_chart/<vehicle_id>'
    And header Authorization = authHeader
    When method get
    Then status 200

Examples:
    | vehicle_id |
    | 65909      |
    | 62758      |

Scenario Outline: Check the response on balancing details
    Given url baseURL + '/balancing_details/<vehicle_id>'
    And header Authorization = authHeader
    When method get
    Then status 200

Examples:
    | vehicle_id |
    | 65909      |
    | 62758      |

#Scenario: Check the response on balancing details
#    Given url baseURL + '/battery_summary_status'
#    And header Authorization = authHeader
#    When method get
#    Then status 200
#
#Scenario: Check the response on balancing details
#    Given url baseURL + '/dashboard_stats'
#    And header Authorization = authHeader
#    When method get
#    Then status 200


Scenario: Check the response on balancing details
    Given url baseURL + '/detail_user_activities'
    And header Authorization = authHeader
    When method get
    Then status 200


# Scenario Outline: Check the response on oil change details
#     Given url baseURL + '/oil_change_details/<vehicle_id>'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
#     And match response.oil_change_data.oil_card.card_title != null
#     And match response.oil_change_data.oil_card.oil_name != null
#     And match response.oil_change_data.oil_card.oil_brand != null
#     And match response.oil_change_data.oil_card.oil_type != null
#     And match response.oil_change_data.filter_card.card_title != null
#     And match response.oil_change_data.filter_card.filter_name != null
#     And match response.oil_change_data.filter_card.filter_brand_name != null

# Examples:
#     | vehicle_id |
#     | 65909         |
#     | 62758       |

Scenario Outline: Check the response on rotation_details
    Given url baseURL + '/rotation_details/<vehicle_id>'
    And header Authorization = authHeader
    When method get
    Then status 200


Examples:
    | vehicle_id |
    | 65909      |
    | 62758      |

Scenario Outline: Check the response on tire_details
    Given url baseURL + '/tire_details/<vehicle_id>'
    And header Authorization = authHeader
    When method get
    Then status 200

Examples:
    | vehicle_id |
    | 65909      |
    | 62758      |


Scenario Outline: Check the response on alignment details
    Given url baseURL + '/alignment_details/<vehicle_id>'
    And header Authorization = authHeader
    When method get
    Then status 200

Examples:
    | vehicle_id |
    | 65909      |
    | 62758      |


# Scenario Outline: Check the response on tire summary physical state
#     Given url baseURL + '/tire_summary_physical_state/<status>'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Examples:
#     | status |
#     | 1      |
#     | 2      |
#     | 3      |
#     | 4      |
#     | 5      |
#     | 6      |
#     | 7      |
#     | 8      |
# 
# Scenario: Check the response on tire life span
#     Given url baseURL + '/tires_lifespan'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# 
# Scenario Outline: Check the response on tire lifespan status
#     Given url baseURL + '/tires_lifespan_status/<status>'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Examples:
#     | status |
#     | 1      |
#     | 2      |
#     | 3      |
#     | 4      |
# 
# Scenario: Check the response on tire require change
#     Given url baseURL + '/tires_require_change'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Scenario: Check the response on user activities
#     Given url baseURL + '/user_activities'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Scenario Outline: Check the response on user batteries
#     Given url baseURL + '/user_batteries/<status>'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Examples:
#     | status |
#     | 0      |
#     | 1      |
#     | 2      |
# 
# Scenario: Check the response on user batteries physical state
#     Given url baseURL + '/user_batteries_physical_state'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Scenario Outline: Check the response on user batteries state
#     Given url baseURL + '/user_batteries_state/<status>'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Examples:
#     | status |
#     | 0      |
#     | 1      |
#     | 2      |
#     | 3      |
#     | 4      |
#     | 5      |
# 
# Scenario Outline: Check the response on user batteries status
#     Given url baseURL + '/user_batteries_status/<status>'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Examples:
#     | status |
#     | 1      |
#     | 2      |
#     | 3      |
#     | 4      |
# 
# Scenario Outline: Check the response on user oilchange status
#     Given url baseURL + '/user_oilchange_status/<status>'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Examples:
#     | status |
#     | 1      |
#     | 2      |
#     | 3      |
# 
# 
# Scenario Outline: Check the response on user tires require change
#     Given url baseURL + '/users_tires_require_change/<status>'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Examples:
#     | status |
#     | 1      |
#     | 2      |
# 
# Scenario Outline: Check the response on user tires require change
#     Given url baseURL + '/users_tires_require_change/<status>'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Examples:
#     | status |
#     | 1      |
#     | 2      |
# 
# Scenario: Check the response on balancing by vehicle
#     Given url baseURL + '/balancing_by_vehicle'
#     And params ({vehicle_id: 65909, service_id: 45559})
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Scenario: Check the response on battery by vehicle
#     Given url baseURL + '/battery_by_vehicle'
#     And params ({vehicle_id: 65909, service_id: 45559})
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Scenario: Check the response on battery by vehicle
#     Given url baseURL + '/tires_by_vehicle'
#     And params ({vehicle_id: 65909, service_id: 45559})
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# 
# Scenario: Check the response on services by user
#     Given url baseURL + '/services_by_user'
#     And params ({user_id: 92913})
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Scenario Outline: Check the response on services by user
#     Given url baseURL + '/vehicles_by_user/<user_id>'
#     And header Authorization = authHeader
#     When method get
#     Then status 200
# 
# Examples:
#     | user_id |
#     | 92913   |
# 
# 
