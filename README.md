# Autocomplete Endpoint

## Introduction

A Drupal 8/9 module that provides a generic HTTP endpoing for exposing data for consumption by Drupal autocomplete form elements. Initially intended to be used with [Linked Data Lookup Field](https://drupal.org/project/linked_data_field).

Currently still a proof of concept.

## Use cases

The Linked Data Lookup Field module "Provides an autocomplete field widget that pulls suggested matches and URLs from authoritative sources." In fields of this type, a subject heading, for example, is human readable, but it also contain the heading's Linked Data URI from an autoritative vocabulary such as the [Library of Congress Subject Headings](http://id.loc.gov/authorities/subjects.html). This means that the heading, and the node it applies to, is part of the Linked Open Data environment and can be used in any tools and services that rely on Linked Data URIs.

The Autocomplete Endpoint module allows a Drupal instance to provide authoritative vocabularies to other Drupal instances (or other consumers of vocabularies). Some use cases for this ability are:

* A university library runs three different [Islandora](https://islandora.ca) instances (an institutional repository, a research data repository, and a repository of digitized images/books/manuscripts), and has a local vocabulary of department names that applies to items in all three repositories.
* A group of libraries running Islandora are collaborating on creating distributed collections on a specific theme, and they want subject specialists from their partner insitutions to assist with creating Islandora objects. With this module, they can all use the same set of locally managed name entities.
* A group of allied community organizations all use Drupal as their CMS, and each runs local training events. Using this module, all members of this group can use the same set of keywords to describe their events, enabling querying/grouping of those events across all members.

In each case, one of the Drupal instances (the one running this module) maintains the shared vocabulary on behalf of the others, but they all use the shared vocabulary locally.

Vocabularies exposed in this way are not limited to use by other Drupal instances. The data that this module exposes is consistent with the data exposed by the Library of Congress and other providers of Linked Data.

## Requirements

* There are no hard requirements for installing this module other than Drupal 8, but data it exposes was initially intended to be consumed by [Linked Data Lookup Field](https://drupal.org/project/linked_data_field).

This module is Drupal 9 ready.

## Installation

1. Clone this repo into your Islandora's `drupal/web/modules/contrib` directory.
1. Enable the module either under the "Admin > Extend" menu or by running `drush en -y autocomplete_endpoint`.

## Configuration

Currently, this module requires no configuration. Configuration of Linked Data Lookup Field (and other consumers) to use this module will be provided soon.

## Usage

This module currently provides two data source plugins, 1) a sample plugin, intended for developers and 2) a 'vocabulary' plugin that exposes terms with URIs to the consumer.

* The endpoint for the "sample" plugin is `/autocomplete_endpoint/sample?q=`
   * For example, entering 'f' in the autocomplete field configured to use this endoint will produce the results `[{"label":"four","uri":"http:\/\/example.com\/four"},{"label":"five","uri":"http:\/\/example.com\/five"},{"label":"fifteen","uri":"http:\/\/example.com\/fifteen"}]`.
* The endpoint for the "vocabulary" plugin is `/autocomplete_endpoint/vocabulary?vid=islandora_models&uri_fields=field_external_uri&q=p`
   * The 'vid' parameter is the machine name (ID) of the vocabulary you want to expose
   * The 'uri_fields' parameter is a comma-separated list of field on the vocabulary that contain URIs.
   * For example, using a standard Islandora 8 Playbook VM as the host, entering 'p' in the autocomplete field configured to use this endpoint (and with the 'vid' and 'uri_fields' values above) will produce the results `[{"label":"Page","uri":"http:\/\/id.loc.gov\/ontologies\/bibframe\/part"},{"label":"Paged Content","uri":"https:\/\/schema.org\/Book"},{"label":"Publication Issue","uri":"https:\/\/schema.org\/PublicationIssue"}]`.

## Current maintainer

* [Mark Jordan](https://github.com/mjordan)

## License

[GPLv2](http://www.gnu.org/licenses/gpl-2.0.txt)
