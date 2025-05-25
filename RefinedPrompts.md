# Refined prompts

They are structured in this way:

1. Subtitle is the tag from the repository
2. Then it comes the actual prompt
3. Finally, a section to follow up with verification of the results (if any)

## breeze

I want this project to be a recipe portal, where users can share and view recipes online.
This is the backend, which will be completely in PHP with laravel, and it will serve as an API engine.
The frontend will be a completely different project, using NextJS and consuming the exposed APIs by this backend.
The models I have considered so far, are based on the operations that the users can do with the recipes.
The operations that users are capable of doing are:
    - See recipes list
        - and be able to sort by:
            - posted date (oldest - newest)
            - popularity (by comments added)
            - rating (by stars given to it)
    - see individual recipes
    - Search for recipes
    - Flag favorite recipes
    - Add comments to recipes
    - Reply to comments in recipes
what are the models that should be created to meet those requirements?

## skeleton

I want now to create resource controllers and migrations for the given models

Add all needed fields, indexes, and everything that's needed in the migration file based on the model information

### Verification

- Make sure the following is in the response and they are run in the terminal

```bash
./vendor/bin/sail artisan make:migration create_users_table
./vendor/bin/sail artisan make:migration create_recipes_table
./vendor/bin/sail artisan make:migration create_comments_table
./vendor/bin/sail artisan make:migration create_ratings_table
./vendor/bin/sail artisan make:migration create_favorites_table

./vendor/bin/sail artisan make:controller UserController --resource
./vendor/bin/sail artisan make:controller RecipeController --resource
./vendor/bin/sail artisan make:controller CommentController --resource
./vendor/bin/sail artisan make:controller RatingController --resource
./vendor/bin/sail artisan make:controller FavoriteController --resource
```

- Run the migrations:

```bash
./vendor/bin/sail artisan migrate
```

## rest

I want now to expose the functionality for this via REST APIs so the frontend can reach them

## categories

I want the recipes to have association with categories.
The categories are a single way to categorize where the recipes belong to, examples of categories: Mexican, Italian, Vegan, Low carb.
One recipe can belong to multiple categories, and one category can have multiple recipes.
Create a category model, a category controller, a category route too.
One recipe can belong to multiple categories and one category can have multiple recipes.

### Verification

- Make sure these attributes exist everywhere (model, controller, migration): description, image_url, slug
- Make sure the filenames of the migrations are consistent with date and time
- Make sure changes are saved to files
- Run migration once more

```bash
./vendor/bin/sail artisan migrate:fresh
```

- Run seeder for user

```bash
./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
```

## tests-and-fixes

- I want to test what I have so far, to make sure that all operations are working as expected
- help me create the tests for all controllers except user and also ignore the ones that have tests already
- Please consider the factories that exist too

### Verification

- Make sure `use RefreshDatabase;` is used
- Make sure phpunit.xml is properly configured

```php
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="APP_MAINTENANCE_DRIVER" value="file"/>
    <env name="BCRYPT_ROUNDS" value="4"/>
    <env name="CACHE_STORE" value="array"/>
    <env name="DB_DATABASE" value="testing"/>
    <env name="MAIL_MAILER" value="array"/>
    <env name="PULSE_ENABLED" value="false"/>
    <env name="QUEUE_CONNECTION" value="sync"/>
    <env name="SESSION_DRIVER" value="array"/>
    <env name="TELESCOPE_ENABLED" value="false"/>
</php>
```

- Make sure field is called comment for comments
- Make sure field is called rating for ratings
- Run the tests

```bash
./vendor/bin/sail artisan test
```

## searching-capabilities

I want to implement now the ability to search so the application can have the ability to expose search capabilities using laravel scout and meilisearch support

### Verification

- Only for Recipe model
- Configure scout.php file

```php
'meilisearch' => [
    'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
    'key' => env('MEILISEARCH_KEY'),
    'index-settings' => [
        // 'users' => [
        //     'filterableAttributes'=> ['id', 'name', 'email'],
        // ],
        Recipe::class => [
            'filterableAttributes' => ['id', 'title', 'description'],
            'sortableAttributes' => ['title', 'description', 'created_at'],
        ],
        'categories' => [
            'filterableAttributes' => ['id', 'name'],
            'sortableAttributes' => ['name', 'created_at'],
        ],
        'questions' => [
            'filterableAttributes' => ['id', 'title', 'question'],
            'sortableAttributes' => ['title', 'question', 'created_at'],
        ],
    ],
],
```

- Configure phpunit.xml for scout with prefix for index: 

```xml
<env name="SCOUT_PREFIX" value="testing_"/>
```

## debugging-with-custom-docker

** This is a manual verification and implementation **

### Verification

- Run:

```bash
./vendor/bin/sail artisan sail:publish
```

- Make sure docker-composer.yml doesn't use references for vendor/sail for images
- Add this to php.ini:

```ini
+[xdebug]
+xdebug.mode=${XDEBUG_MODE}
```

- Add the following to .env:

```env
SAIL_XDEBUG_MODE=develop,debug,coverage
```

- Build docker images by running:

```bash
./vendor/bin/sail build –no-cache
```

- Configure bindings in PHPStorm: [your_repo_dir] -> /var/www/html

## authentication-authorization

now I want to add authorization to edit and delete operations, so it happens that only the users who created a particular record can edit it or delete it.
No other user should be able to edit or delete records that were not created by that user.
Take into consideration the Recipe model and apply the changes to the recipe controller, also, add test cases for the policies scenarios. Do not generate an AuthServiceProvider suggestion.

### Verification

Make sure:
- Policies are created
- There's no need to have an AuthServiceProvider
- Authorization logic is in policies for operations
- The names of the operations are the methods in the policies (update, delete)
- Apply authorization in controllers
- Tests are added
- Run the tests

```bash
./vendor/bin/sail artisan test
```

- If tests fail, put "actingAs($user)"
- Debug if something fails
- Categories are not created / updated by customer users

## validations

** Do verification first **

I want to add validation to the operations of the controllers for all the models now

### Verification

- Make sure validations are good in case they exist already

## categories-more-fields

** Do verification first **

I want to add an attribute to the categories, an image, that is required.
I want to consider any other attributes that I need for the category.

### Verification

- Make sure first that the fields are already there

## categories-seeder

I want now to create a seeder for categories, but something that has real potential values for recipe categories.
I want the seeder to generate real records based on those real values for recipes categories and fill the fields for name, slug and description, and leave image out.

### Verification

Put this as the images in the seeder:

```php
'image' => 'http://localhost:3000/images/categories/'.Str::slug($category['name']).'.svg',
```

- Run the seeder

```bash
./vendor/bin/sail artisan db:seed --class=CategorySeeder
```

## categories-images

** For this go to pixabay.com and get the images instead **

Categories images generation using gemini AI.
generate an image for food category that refers to Appetizers. The image will be used in a web application.
The format has to be png or svg with transparent background. Small size.

### Verification

Or go to pixabay.com to get one image instead

## recipe-more-fields

Add these fields to the recipes (all are numbers):
- prep time hours
- prep time minutes
- cook time hours
- cook time minutes
- servings
- calories
Also generate a migration for them

### Verification

Make sure these files are considered:
- Preparation time is `prep_*` for the fields (instead of `preparation_*`)
- Model
- migration
- Controller for validation
- Controller test
- Factory
- Run migration

```bash
./vendor/bin/sail artisan migrate:fresh
```

- Rerun tests

```bash
./vendor/bin/sail artisan test
```

## recipe-seeder

I want now to create a seeder for recipes, but something that has real potential values for recipes.
Consider all current existent fields for recipes.
I want the seeder to generate real records based on those real values for recipes and fill their fields.
For image_url fields, fill them with something like the following: "http://localhost/images/recipes/{slug}.jpeg"
generate more records, to have at least eight records seeded. And also please include the associations to one or more categories

### Verification

Make sure:
- At least eight records are created
- User id is generated
- Category ids are considered
- Run the migration with refresh

```bash
./vendor/bin/sail artisan migrate:fresh
```

- Both category and recipe seeders are added to Database seeder
- Run the seeder

```bash
./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
```

## aggregated-fields

I want now to have aggregated fields in recipes for:
- Favorites: favorites_count
- Ratings: sum, count, average
Favorites count aggregated field value in recipe should be updated to be accumulated when a favorite record is created and decreased when a record is deleted in the favorite controller
Rating aggregated fields values in recipe should be updated accordingly too based on the addition, edition or deletion of records through rating controller
Tests should be considered too

### Verification

Make sure:
- Tests have `actAsUser`
- Creation records have `user_id` inside the post in the tests
- The policies for favorites and ratings allow edition and deletions
- the `rating_sum` and `rating_count` won't be less than 0
- the `rating_avg` won't get division by 0
- the `favorite_count` won't be less than 0
- the new aggregate fields are present in the fillable attribute in the model
- a migration is created with proper timestamp in filename
- run migration

```bash
./vendor/bin/sail artisan migrate:fresh
```

- Run tests

```bash
./vendor/bin/sail artisan test
```

## recipe-by-slug

I want now to have the ability to get the recipe by slug.
Given the fact that the slug is unique and the frontend would request categories by slug rather than by id

### Verification

Make sure:
- tests are added too
- route is added too
- include "with" for ratings, favorites and comments
- run tests

```bash
./vendor/bin/sail artisan test
```

## recipes-by-category-slug

I want now to have the ability to get all the recipes associated to a category, by providing a category slug

### Verification

Make sure:
- The route to the slug is: `/recipes/category-slug/{slug}`
- The relationship for recipes in category model is good (with belongsToMany and extra details)
- tests are added too
- route is added too
- run tests

```bash
./vendor/bin/sail artisan test
```

## recipes-associations-to-categories

associations are not being persisted in the DB when using attach method

## search-improvements

** This is a manual verification and implementation **

### Verification

Make sure:
- categories are considered in Recipes controller
- load categories in toSearch array in recipes
- this is run in the recipe controller:

```php
$recipe→searchable();
```

- associations between recipes and categories are good, with belongToMany and extra details)
- All fields are returned in to search array, including: created_at (with timestamp), categories, image_url, preparation_time, cook_time, favorite_count, rating_count, rating_average, servings, calories
- Configure scout to include
    - Searchable attributes: title, description, ingredients, instructions
    - filterable attributes: preparation_time, cooking_time, favorite_count, rating_count, rating_average, servings, calories, categories
    - sortable attributes: title, created_at, favorite_count, rating_count, rating_average
    - default value for aggregated fields is 0 in the Recipe model
- Run migrate fresh

```bash
./vendor/bin/sail artisan migrate:fresh
```

- Run scout resync

```bash
./vendor/bin/sail artisan scout:sync-index-settings
```

- Run seeder

```bash
./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
```

- Run import models for scout

```bash
./vendor/bin/sail artisan scout:import "App\Models\Recipe"
```

## ratings-favorites-fixes-improvements

** This is a manual verification and implementation **

### Verification

Make sure:
- user_id is used instead in the controllers when storing data for favorites and ratings:

```php
$validated['user_id'] = auth()→user()→id;
```

- From recipes controller, ratings and favorites are not returned with:

```php
->with(['favorites', 'ratings', ‘categories'])
```

- From recipes model, favorites and ratings just return records for current user by using:

```php
->where('user_id', auth()→id());
```

## sample-data-seeder-setup

I want now to have seeders for both Ratings and favorites that are associated to recipes
Please generate at least 7 records for each

### Verification

Make sure:
- to include the seeders with the other ones for categories and recipes
- to run seeder for ratings
- to run seeder for favorites

```bash
./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
```

- seeders save the aggregated fields for recipes

## update-by-slug-with-put

** This is a manual verification and implementation **

### Verification

Make sure:
- The update and the update by slug are equal, the only difference should be the way it gets the original recipe
- To add the route in the api.php file
- To add a test case
- To use sanctum middleware
- Categories are in validation in the updateBySlug method

## delete-by-slug

** This is a manual verification and implementation **

### Verification

Make sure:
- The delete and the delete by slug are equal, the only difference should be the way it gets the original recipe
- To add the route in the api.php file
- To add a test case
- To use sanctum middleware

## comments-implementations

I want now to have the ability to add comments to recipes.
Things to consider when adding this functionality:
- comments can have replies, but in a single level only, meaning, a reply of a comment cannot have replies
- aggregated field comments_count should be updated in recipe whenever a comment is added or deleted (through the comments controller)
- comments should have an aggregated field replies_count
- replies_count should be updated whenever a reply is added or deleted from a parent comment
- comments should be able to be gotten filtering by recipe slug
- replies should be able to be gotten for a given comment_id
- One comment can have multiple replies
- One reply belongs to a single parent comment
- a migration should be created
- a seeder should be created too
- test cases should be added too
- consider the existent functionality for favorites or ratings as a foundation of this functionality

### Verification

Make sure:
- `comments_count` is present in fillable and search array in recipe model
- `comments_count` is updated in the seeder
- `replies_count` is updated in the seeder
- comments are filtered to return only parent comments:
- 
```php
->where('parent_id', null)→limit(10);
```

- Do not filter by user_id
- aggregated field `comments_count` in recipe model is present
- relationship in recipe model has this for comments:

```php
->where('user_id', auth()→id());
```

- Relationship in model for comments
- In comments controller, the user_id is taken from session:

```php
$validated['user_id'] = auth()→user()→id;
```

- show by recipe slug
- route is added to api for comments by recipe slug and comments by parent id
- `replies_count` is present in comment model in fillable
- relationships with recipe and parent are present in comment model
- The seeder is in good shape
- The `comments_count` is in the scout configuration for sortable configuration
- Tests are present for replies to other comments and to not being able to reply to replies
- Test is present for `test_comments_count_increment_on_comment_creation`
- seeders save the aggregated fields for recipes. Recipe should increase the comments in the seeder for comments
- Sanctum middleware is used in the api route configuration
- Run migration

```bash
./vendor/bin/sail artisan migrate:fresh
```

- Run tests

```bash
./vendor/bin/sail artisan test
```

- Run seeder

```bash
./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
```

## github-actions

I'd like to incorporate github actions for this project, is this something that you can help with? Consider that this is a project written in PHP with laravel and it uses mysql


