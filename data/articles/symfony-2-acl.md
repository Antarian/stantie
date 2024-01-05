---
title: >-
  Symfony 2 advanced/detached ACL
preview:  >-
  In Symfony 2 you can use advanced/detached ACL (access control layer). It’s really fast and I think well engineered. Many people use or try to use ACL tightly coupled with code. This is done not only in Symfony framework.
slug: 'symfony-2-advanced-detached-acl'
categorySlug: 'architecture'
seriesSlug: null
seriesPart: null
archived: true
author: 'Peter Labos'
published: '17th Mar 2016'
---
# Symfony 2 advanced/detached ACL

In Symfony 2 you can use advanced/detached ACL (access control layer). It’s really fast and I think well engineered. Many people use or try to use ACL tightly coupled with code. This is done not only in the Symfony framework.

You can imagine simple coupled ACL as bodyguard in club asking a person:
- B: Who you are?
- P: I am the owner of the club.
- B: OK, you can go in.

Advanced/detached ACL:
- B: This is Club 1 (`object identity`), access with Club 1 VIP card (`security identity`) only.
- P: I am the owner of the club.
- B: I don't care, do you have a Club 1 VIP card?
- P: Not with me.
- B: OK, not going (`access control entry`) in.

Using simple coupled ACL in lists/datasets for in SQL filtering is the most common thing in web development. This implementation brings some positive, but many negative things.

Positive:
- admin/superadmin can control almost whole application flow without additional code or changing ACL
- less tables in database for ACL
- quicker and easier for CRUD or MVP(minimum viable product) application type

Negative:
- SQL queries are coupled with ACL and there is no distinction between creator (DB column) and owner (ACL view, edit, delete rights)
- more complicated use of database abstract layer
- code is polluted with access rules beyond the app layer
- code is not reusable in another part of the system
- possible conflicting situations in ACL filtering
- more difficult if not impossible testing without creating fake user
- functional tests require user mocks as ACL is hard to disable

Real life example:
Imagine a list of attendees to Math course with full names only. Math teacher can have rights to see an attendee list, but have no rights to view attendee details.

You can now say:
- I want math teacher to still see a list of attendees, but also access their full details, but only for Math course.

With tightly coupled ACL, I can ask:
- Do you want to promote him to "course manager"? But he will have access to change all courses.
- or add him a role of "office assistant"? He will see attendee details, but in any course.
- or we will hard-wire new rule to the code for teachers with access to course

With detached ACL:
- we add new `object identity` for our `Course` object, this should be already there
- we add `security identity` for our `teacher` role or maybe only for `user`, this also should already be there
- we add new `ACE` (access control entry) rule in the `acl_entries` table to pair security identity, object identity and action, eg. `view-course-attendees-details`
- we add `ACE` check over the `viewCourseAttendeesDetails()` method in `Course` class, if it is not already there

Detached ACL is more work to implement at the start, but it is easier to add things or list rules later. It is also solving the complexity of larger ACLs.
