---
title: >-
  KISS (Keep It Simple, Stupid) principle
preview: null
slug: 'kiss'
categorySlug: 'principles'
seriesSlug: 'basic-principles'
seriesPart: 2
archived: false
author: 'Peter Labos'
published: '16th Mar 2016'
---
# KISS (Keep It Simple, Stupid) principle

## Practical part
- method should have a clear name describing purpose
- class should only do one thing
- lists, like countries, languages, currencies, payment methods, should always be stored in config or database, never item per class

## Theory behind
> Il semble que la perfection soit atteinte non quand il n’y a plus rien à ajouter, mais quand il n’y a plus rien à retrancher.
> 
> (It seems that perfection is finally attained not when there is nothing more to add, but when there is nothing more to take away.)
>> – Antoine de Saint Exupéry

One of the few basic principles, which every programmer should know. Others are DRY and YAGNI. Keep your code as simple as it can be. Which will result in more readable, optimized and reusable code. For example, no method in any class should be larger than 40 lines of code and two levels of nesting.

In relational database design, this principle is used for dividing structure to most basics functional units. For example, using “first name” and “last name” columns, not one “name” column. Using standalone table for “cities” in relation with “address” table, not just one “city” column in “address” table.
