# Wonde Technical Exercise

## Task Details
### User Story:
As a Teacher I want to be able to see which students are in my class each day of the week so that I can be suitably prepared.

### Notes:

You can use any technology that you think is suitable for this task.

The brief has been kept deliberately vague to allow you scope to apply your own assumptions and interpretations and to demonstrate your skills.

## Assumptions

* There is a stable and secure login system in place
* A teacher would have a login to get to a full admin panel
* Data woudld be pulled from the Wonde API periodically (I think I saw hourly mentioned?) using a queue system

## Caveats

* The queue system is replaced by an Artisan command that can be run manually
* The Teacher login is manually created from the first employee in the Wonde API that has classes
  * Email is `Dumbell@example.com`
  * Password is `Password123`

## Files/folders of note

* `app/Http/Controllers/TeacherController.php` - The main controller for the teacher dashboard 