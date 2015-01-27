# Knp Rad Resource Resolver

## Why using it?
Tired of doing the same things again and again in your controllers, like transforming a URL value in an object?
Don't want to use ParamConverter Annotations?

This Resource Resolver is for you.

## How to use it?

In a yaml routing file, it could look like this :

    users_show:
        path: /users/{id}
        defaults:
            _resources:
                user: @my.user.repository::find($id)
    # You will have a $user object in your request attributes
        
    countries_cities_buildings_index:
        path: /countries/{countryId}/cities/{citySlug}/buildings
        defaults:
            _resources:
                buildings: @app.building.repository::findByCountryAndCityAndActivity($countryId, $citySlug, "School")
    # You will have a $buildings object in your request attributes

Every `key` under `_resources` will be return as a `$key` converted value in your controller.

## License
This project is published under MIT License. Feel free to contribute.

