# DispatchDates
This is a none framework specific php file that when ran gives you the earilest day you will expect the delivery based on factors including order time, dispatch exceptions, delivery exceptions and the delivery time.

Change the variables at the top of the php files to data needed and run the php file from terminal.

Variables:
$orderDate = '2024-01-19 19:00:00'; // order date and time it was created
$dispatchDays = [1, 2, 3, 4, 5]; // array of numbered days that can be dispatched, remove or add 6 or 7 for Sat/Sun. 1 is Monday, 2 is Tuesday etc
$dispatchExceptions = '22/01/2024'; //Using this date format due to the way this would be inputted in settings
$dispatchCutOffTime = '17:00:00'; // time in which the cut off for same day dispatch is set. Any order time after this will have to be dispatched the following day
$deliveryDays = [1, 2, 3, 4, 5]; // same as dispatch days
$deliveryExceptions = '22/01/2024
                       30/01/2024'; // same as dispatch exceptions except this is an example of a new line seperated exception list
$deliveryMethod = 1; // how many days does it take to deliver when dispatched
