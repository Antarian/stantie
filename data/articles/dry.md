---
title: >-
  DRY (Don't Repeat Yourself) principle
preview: null
slug: 'dry'
categorySlug: 'principles'
seriesSlug: 'basic-principles'
seriesPart: 1
archived: false
author: 'Peter Labos'
published: '16th Mar 2016'
---
# DRY (Don't Repeat Yourself) principle

## Practical part
- if code repeats in two methods in the same class, place repeated code inside protected or private method and use that method by the other two
- if your code is same between classes, create parent class from duplicated code
- if parent class is not suitable, because child classes are not related, you should create Trait, or other horizontal hierarchy
- if code is much larger than one class, create service(s)
- then module, microservice, domain ... just don't repeat if possible

## Theory behind
Very practical principle and is perfect for lazy people. Are you lazy? I am. I don’t like typing too much of code if it can be used from another location in the project. DRY save yours (and also someone else) time, makes your code better, more readable and also more isolated and reusable. I can cite wikipedia here: Every piece of knowledge must have a single, unambiguous, authoritative representation within a system., but this may sound complicated to get a picture, and it is more about how DRY on the larger scale is leading to Single responsibility principle (S from SOLID).

DRY in short is to keep in mind if you are doing something like “copy – paste” of code or recreating the same functionality in your code, to stop and ask yourself what is wrong. Is it really necessary? Can I just ask some service to do a work or use some class or trait which is already do the thing what I want?

100% DRY is almost unachievable in the real world. Like REST controllers will look almost all the same. And is better to use generator for some things than to merge them into one class. But WET (we enjoy typing) can slow you down or even stop in what you are trying to achieve, by making your code very unreadable and hard to maintain.

## Sources
- Pragmatic programmer - Andy Hunt and Dave Thomas
