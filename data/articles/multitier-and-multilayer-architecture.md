---
title: >-
  Multitier and multilayer architecture
preview:  >-
  Tier and layers are often very similar to each other in names. That can be confusing, but they describe different things.
slug: 'multitier-and-multilayer-architecture'
categorySlug: 'architecture'
seriesSlug: null
seriesPart: null
archived: false
author: 'Peter Labos'
published: '15th May 2016'
---
# Multitier and multilayer architecture

Tiers and layers are often very similar to each other in names. That can be confusing, but they describe different things.

**Tiers** are about how you divide your application into different physical representations, you can have one or multi-tier application with multiple layers:
- *Presentation tier* can be only user device, think about application which only displays data from some API on mobile or printer which prints data.
- *Application tier* can be a server which computes your data and for that it can communicate with data tier.
- *Data tier* is a physical database server or storage service (cloud storage) on more servers to read/write data for your application server.

**Layers** are about how you divide the functionality of code.
- *Presentation layer* is responsible for presenting data.
- *Data layer* is responsible for data storing and reading.
- *Business layer* is responsible for communication between layers and computing.

Many modern frameworks and apps are using layered design, because it's much easier to maintain or grow systems in this type of architecture.
