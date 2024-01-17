<?php

//Change variables as needed
$orderDate = '2024-01-19 19:00:00';
$dispatchDays = [1, 2, 3, 4, 5]; // array of numbered days that can be dispatched, remove or add 6 or 7 for Sat/Sun
$dispatchExceptions = '22/01/2024'; //Using this date format due to the test instruction requirements
$dispatchCutOffTime = '17:00:00';
$deliveryDays = [1, 2, 3, 4, 5];
$deliveryExceptions = '22/01/2024
                       30/01/2024'; //example of a new line seperated exception list
$deliveryMethod = 1; //how many days delivery the method has

echo getEarliestDeliveryDate($orderDate, $dispatchDays, $dispatchExceptions, $dispatchCutOffTime, $deliveryDays,
    $deliveryExceptions, $deliveryMethod);

function getEarliestDeliveryDate($orderDate, $dispatchDays, $dispatchExceptions, $dispatchCutOffTime, $deliveryDays, $deliveryExceptions, $deliveryMethod): string
{
    $orderDateStrToTime = strtotime($orderDate);
    $deliveryDate = $orderDateStrToTime; //Variable to keep track of the delivery date, this will get updated as we go along

    // Add a day if order date is after the cut-off
    $dispatchCutOff = strtotime(date('Y-m-d', $orderDateStrToTime) . ' ' . $dispatchCutOffTime);
    if ($orderDateStrToTime > $dispatchCutOff) {
        $deliveryDate = strtotime('+1 day', $orderDateStrToTime);
    }

    // While loop in case changing the dispatch date due to allowed days means the new dispatch date is a bank holiday
    $dateChanged = true;
    while ($dateChanged == true)
    {
        $oldDeliveryDate = $deliveryDate;
        // Check the dispatch exceptions and then days of the week
        $deliveryDate = checkExceptions($deliveryDate, $dispatchExceptions);
        $deliveryDate = checkDays($deliveryDate, $dispatchDays);
        if ($deliveryDate == $oldDeliveryDate) {
            $dateChanged = false;
        } else {
            $dateChanged = true;
        }
    }

    //Add the delivery time now we have the dispatch date
    $deliveryDate = strtotime("+$deliveryMethod day", $deliveryDate);

    // Another while loop for above reason but with delivery dates not dispatch dates
    $dateChangedDel = true;
    while ($dateChangedDel == true) {
        $oldDeliveryDate = $deliveryDate;
        //Check the delivery exceptions and then days of the week
        $deliveryDate = checkExceptions($deliveryDate, $deliveryExceptions);
        $deliveryDate = checkDays($deliveryDate, $deliveryDays);
        if ($deliveryDate == $oldDeliveryDate) {
            $dateChangedDel = false;
        } else {
            $dateChangedDel = true;
        }
    }

    return date('l, Y-m-d', $deliveryDate);
}

/**
 * Created as duplicate code for dispatch and delivery exceptions checks
 *
 * @param $deliveryDate
 * @param $exceptionsList
 * @return false|int|mixed
 */
function checkExceptions($deliveryDate, $exceptionsList)
{
    $dispatchExceptions = explode("\n", $exceptionsList);
    foreach ($dispatchExceptions as $exceptionDate) {
        $exceptionDate = trim($exceptionDate);
        $exceptionDate = str_replace('/', '-', $exceptionDate);
        //Converted deliverydate instead of strtotime exceptiondate due to mismatches in timestamps if day same but hour different
        if (date('d-m-Y', $deliveryDate) == $exceptionDate) {
            $deliveryDate = strtotime('+1 day', $deliveryDate);
        }
    }

    return $deliveryDate;
}

/**
 * Created as duplicate code for dispatch and delivery days of the week checks
 *
 * @param $deliveryDate
 * @param $daysList
 * @return false|int|mixed
 */
function checkDays($deliveryDate, $daysList)
{
    $dayOfDispatch = date('N', $deliveryDate);
    while (!in_array($dayOfDispatch, $daysList)) {
        $deliveryDate = strtotime('+1 day', $deliveryDate);
        $dayOfDispatch = date('N', $deliveryDate);
    }

    return $deliveryDate;
}
