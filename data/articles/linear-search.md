---
title: >-
  Linear search algorithm (in PHP)
preview: null
slug: 'linear-search-algorithm'
categorySlug: 'math'
seriesSlug: 'algorithms-basics'
seriesPart: 1
archived: false
author: 'Peter Labos'
published: '13th Jan 2024'
---
# Linear search algorithm (in PHP)

This is definition from [Wikipedia page](https://en.wikipedia.org/wiki/Linear_search).
> A linear search sequentially checks each element of the list until it finds an element that matches the target value. If the algorithm reaches the end of the list, the search terminates unsuccessfully.

If you are not math person, your brain will probably stop working after words `linear` and `sequentially`.
Meaning:
`Linear` - arranged along a straight line
`Sequentially` - in a way that follows a particular order, ex. one-by-one

In simpler words, if we want to find 1 item in the list of items, we will check each one of them until we find it. This is simple and quick if you searching something in your shopping list of 10 items. But imagine to find one word in your favourite book. As words are not ordered linear search will take long time.

PHP functions like `in_array()` or `array_search()` are using `linear search`. This is because PHP arrays can contain multiple types of data by design (int, string, float, array, bool, object) and it is not possible to sort them without user defined preferences.

Because PHP has no native default implementation of binary search, it is relying on other technologies to search in larger data sets, like SQL, ElasticSearch or custom user defined binary search functions.

## Basic algorithm

From Wikipedia
> Given a list L of n elements with values or records L<sub>0</sub> .... L<sub>n−1</sub>, and target value T, the following subroutine uses linear search to find the index of the target T in L.
> 1. Set i to 0.
> 2. If L<sub>i</sub> = T, the search terminates successfully; return i.
> 3. Increase i by 1.
> 4. If i < n, go to step 2. Otherwise, the search terminates unsuccessfully.

If we have `10 items (list)`, we will compare them one-by-one to `searched item (target)` and also check for every item if we reached end of the list.

Yes, that sounds tedious and at least in PHP and C that is exactly what `for` loop is doing. `for` loop will check condition after every pass.

Raw and crude implementation of this in PHP may look like this:
```php
<?php
$target = 'U';
$list = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'];
$n = 10;
for ($i = 0; $i < $n, $i++) {
    if ($list[$i] === $target) {
        die($i);
    }
}
die('Not found');
```

## With a sentinel
From Wikipedia:
> The basic algorithm above makes two comparisons per iteration: one to check if L<sub>i</sub> equals T, and the other to check if i still points to a valid index of the list. By adding an extra record L<sub>n</sub> to the list (a sentinel value) that equals the target, the second comparison can be eliminated until the end of the search, making the algorithm faster. The search will reach the sentinel if the target is not contained within the list.
> 1. Set i to 0.
> 2. If L<sub>i</sub> = T, go to step 4.
> 3. Increase i by 1 and go to step 2.
> 4. If i < n, the search terminates successfully; return i. Else, the search terminates unsuccessfully.

We improve previous style with removing of check on every step. We then add item we search on the end of the array. If returned index is same as the end one, item is not in the array.

In PHP this means to change our `for` loop to `foreach`.

Raw and crude implementation of this in PHP may look like this:
```php
$target = 'A';
$list = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P']; 
array_push($list, $target); // target added to end of array
$n = 10;
$found = null;
foreach ($arr as $i => $item) {
    if ($item === $target) {
        $found = $i;
        break;
    }
}
if ($n === $found)
    die('Not found');

die($found);
```

## In an ordered table
From Wikipedia:
> If the list is ordered such that L0 ≤ L1 ... ≤ Ln−1, the search can establish the absence of the target more quickly by concluding the search once Li exceeds the target. This variation requires a sentinel that is greater than the target.
> 1. Set i to 0.
> 2. If L<sub>i</sub> ≥ T, go to step 4.
> 3. Increase i by 1 and go to step 2.
> 4. If L<sub>i</sub> = T, the search terminates successfully; return i. Else, the search terminates unsuccessfully.

Ordering array before search will add time to algorithm. If you need to order the array only because of search, then it is better to use [binary search](binary-search.html).

Raw and crude implementation of this in PHP may look like this:
```php
$target = 'A';
$list = ['E', 'I', 'O', 'P', 'Q', 'R', 'T', 'U', 'W', 'Y']; // alphabetically ordered list 
$n = 10;
$found = null;
foreach ($arr as $i => $item) {
    if (strcmp($item, $target) >= 0) { // compare strings by alphabet
        if ($item == $target) {
            die($i);
        }
        die('Not found');
    }
}
```
