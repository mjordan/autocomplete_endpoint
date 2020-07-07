# Autocomplete Endpoint

## Introduction

A Drupal 8/9 module that provides a generic HTTP endpoint for exposing vocabularies for consumption by Drupal autocomplete form elements. Initially intended to be used with [Linked Data Lookup Field](https://drupal.org/project/linked_data_field).

Here, "vocabularies" are not necessarily Drupal vocabularies (although they can be; they can also be nodes), they are any collection of terms intended for reuse by a network of consumers. This module provides the ability for vocabularies to be maintained in a "provider" Drupal instance; these vocabularies can then be used by "consumer" Drupal instances. The result is that all consumers of a vocabulary share Linked Data that can be used to query, traverse, and aggregate content from all members of the network.

## Use cases

The Linked Data Lookup Field module "Provides an autocomplete field widget that pulls suggested matches and URLs from authoritative sources." In fields of this type, a subject heading, for example, is human readable, but it also contain the heading's Linked Data URI from an autoritative vocabulary such as the [Library of Congress Subject Headings](http://id.loc.gov/authorities/subjects.html). This means that the heading, and the node it applies to, is part of the Linked Open Data environment and can be used in any tools and services that rely on Linked Data URIs.

The Autocomplete Endpoint module allows a Drupal instance to provide authoritative vocabularies to other Drupal instances (or other consumers of vocabularies). Some use cases for this ability are:

* A university library runs three different [Islandora](https://islandora.ca) instances (an institutional repository, a research data repository, and a repository of digitized images/books/manuscripts), and has a local vocabulary of department names that applies to items in all three repositories.
* A group of libraries running Islandora are collaborating on creating distributed collections on a specific theme, and they want subject specialists from their partner insitutions to assist with creating Islandora objects. With this module, they can all use the same set of locally managed name entities.
* A group of allied community organizations all use Drupal as their CMS, and each runs local training events. Using this module, all members of this group can use the same set of keywords to describe their events, enabling querying/grouping of those events across all members.

In each case, the "provider" Drupal instance (the one running this module) maintains the shared vocabulary on behalf of the "consumers", which run the Linked Data Lookup Field module. The provider can itself run the Linked Data Lookup Field module and use the shared vocabulary, making it both the provider and a consumer.

Vocabularies exposed in this way are not limited to use by other Drupal instances. The data that this module exposes is consistent with the data exposed by the Library of Congress and other providers of Linked Data.

## Requirements

There are no hard requirements for installing this module other than Drupal 8, but data it exposes was initially intended to be consumed by [Linked Data Lookup Field](https://drupal.org/project/linked_data_field).

This module is Drupal 9 ready.

## Installation

1. Clone this repo into your Islandora's `drupal/web/modules/contrib` directory.
1. Enable the module either under the "Admin > Extend" menu or by running `drush en -y autocomplete_endpoint`.

## Configuration

This module has no admin settings. 

## Usage

### On the Drupal instance exposing the data (the "provider")

This module currently provides three data source plugins, 1) a 'vocabulary' plugin that exposes terms with URIs to the consumer, and 2) a 'node' plugin that exposes titles and URIs of nodes, and 3) a plugin intended as a simple example for developers.

* The endpoint for the "vocabulary" plugin is `/autocomplete_endpoint/vocabulary?vid=islandora_models&uri_fields=field_external_uri&q=`
   * The 'vid' parameter is the machine name (ID) of the vocabulary you want to expose.
   * The 'uri_fields' parameter is a comma-separated list of field on the vocabulary that contain URIs.
   * For example, using a standard Islandora 8 Playbook VM as the host, entering 'p' in the autocomplete field configured to use this endpoint (and with the 'vid' and 'uri_fields' values above) will produce the results `[{"label":"Page","uri":"http:\/\/id.loc.gov\/ontologies\/bibframe\/part"},{"label":"Paged Content","uri":"https:\/\/schema.org\/Book"},{"label":"Publication Issue","uri":"https:\/\/schema.org\/PublicationIssue"}]`.

> Note that even though the `vocabulary` plugin exposes Drupal vocabulary terms and their URIs, the Linked Data Lookup field type in the consuming Drupal is not a taxonomy reference field, it is a structured field comprised of two text subfields, one for the label and the other for the URI. In other words, the source Drupal manages the Linked Data vocabulary as a standard Drupal vocabulary (with a URI field added) but the consuming Drupal stores the exposed Linked Data as pairs of labels and URIs.

* The endpoint for the "node" plugin is `/autocomplete_endpoint/node?contenttype=my_content_type&uri_fields=field_uri&q=`
   * The 'contenttype' parameter is the machine name of the content type of the nodes you want to expose.
   * The 'uri_fields' parameter is a comma-separated list of field on the vocabulary that contain URIs. These fields should have a maximum of 1 value (i.e., not multivalued).
   * For example, using nodes of content type `my_content_type` with a field `field_uri`, entering 'd' in the autocomplete field configured to use this endpoint will produce the results `[{"label":"Dogs","uri":"http:\/\/example.com\/dogs"},{"label":"Donuts","uri":"http:\/\/example.com\/donuts"}]`.

* The endpoint for the "sample" plugin is `/autocomplete_endpoint/sample?q=`
   * For example, entering 'f' in the autocomplete field configured to use this endoint will produce the results `[{"label":"four","uri":"http:\/\/example.com\/four"},{"label":"five","uri":"http:\/\/example.com\/five"},{"label":"fifteen","uri":"http:\/\/example.com\/fifteen"}]`.

### On the Drupal instance running Linked Data Lookup Field (the "consumer")

To add a new endpoint field to a content type that consumes a shared Linked data vocabulary, do the following:

* Go to Admin > Structure > Linked Data Lookup Endpoint > Add Linked Data Lookup Endpoint.
* Label: up to you.
* Endpoint type: `URL Argument Type`
* Base URL
   * If you are using the `vocabulary` plugin: [your Drupal's base URL]`/autocomplete_endpoint/vocabulary?vid=islandora_models&uri_fields=field_external_uri&q=` (`vid` and `uri_fields` values will vary depending on which vocabulary is being exposed; `q=` should be at the end) 
   * If you are using the `node` plugin: [your Drupal's base URL]`/autocomplete_endpoint/node?contenttype=my_content_type&uri_fields=field_uri&q=` (`contenttype` and `uri_fields` values will vary depending on which content type is being exposed; `q=` should be at the end) 
* Result record JSON path: `[*]`
* Label JSON key: `label`
* URL JSON key: `uri`

Your endpoint is now configured as a field that can be added to a content type. To add it, go to Admin > Structure > Content types > [your content type] > Manage fields > Add field > and choose "Linked Data Lookup Field" as the field type, then choose the new endpoint you created following the instructions above.

## Current maintainer

* [Mark Jordan](https://github.com/mjordan)

## License

[GPLv2](http://www.gnu.org/licenses/gpl-2.0.txt)
