# Autocomplete Endpoint

## Introduction

A Drupal 8/9 module that provides an HTTP endpoint for exposing vocabularies for consumption by Drupal autocomplete form elements. Initially intended to be used with [Linked Data Lookup Field](https://drupal.org/project/linked_data_field), which "Provides an autocomplete field widget that pulls suggested matches and URLs from authoritative sources." The Autocomplete Endpoint module enables Drupal to be an authoritative source for Linked Data vocabularies.

Here, "vocabularies" are not necessarily Drupal vocabularies (although they can be, but they can also be groups of nodes, as explained below). "Vocabularies" are any collection of terms intended for reuse by Linked Data consumers. This module enables vocabularies to be maintained in a "provider" Drupal instance; these vocabularies can then be used by any number of "consumer" Drupal instances. The result is that all consumers of a vocabulary share Linked Data that can be used to query, traverse, and aggregate content across all members of the network.

## Use cases

Some use cases for this ability are:

* A university library runs three different [Islandora](https://islandora.ca) instances (an institutional repository, a research data repository, and a repository of digitized images/books/manuscripts), and has a local vocabulary of department names that applies to items in all three repositories.
* A group of libraries running Islandora are collaborating on creating distributed collections on a specific theme, and they want subject specialists from their partner insitutions to assist with creating Islandora objects. With this module, they can all use the same set of locally managed name entities.
* A group of allied community organizations all use Drupal (not Islandora) as their CMS, and each runs local training events. Using this module, all members of this group can use the same set of keywords to describe their events, enabling querying/grouping of those events across all members.

In each case, the "provider" Drupal instance (the one running this module) maintains the shared vocabulary on behalf of the "consumers", which run the Linked Data Lookup Field module. The provider can itself run the Linked Data Lookup Field module and use the shared vocabulary, making it both the provider and a consumer.

Vocabularies exposed in this way are not limited to use by other Drupal instances. The data that this module exposes is consistent with the data exposed by the [Library of Congress](http://id.loc.gov/) and other providers of Linked Data vocabularies.

## Requirements

There are no hard requirements for installing this module other than Drupal 8, but data it exposes was initially intended to be consumed by [Linked Data Lookup Field](https://drupal.org/project/linked_data_field).

This module is Drupal 9 ready.

## Installation

1. Clone this repo into your Islandora's `drupal/web/modules/contrib` directory.
1. Enable the module either under the "Admin > Extend" menu or by running `drush en -y autocomplete_endpoint`.

## Configuration

Enpoints are configured at `/admin/autocomplete_endpoint`. Once they exist, they can be used as described below.

## Usage

### On the Drupal instance exposing the data (the "provider")

* The endpoint for the "vocabulary" service is `/autocomplete_endpoint/vocabulary?vid=islandora_models&uri_fields=field_external_uri&q=`
   * The "vid" parameter is the machine name (ID) of the vocabulary you want to expose.
   * The "uri_fields" parameter is a comma-separated list of field on the vocabulary that contain URIs.
   * The "q" parameter is popluated by the Linked Data Lookup Field module and should be at the end of the URL.
   * For example, using a standard Islandora 8 Playbook VM as the host, entering "p" in the autocomplete field configured to use this endpoint (and with the "vid" and "uri_fields" values above) will produce the results `[{"label":"Page","uri":"http:\/\/id.loc.gov\/ontologies\/bibframe\/part"},{"label":"Paged Content","uri":"https:\/\/schema.org\/Book"},{"label":"Publication Issue","uri":"https:\/\/schema.org\/PublicationIssue"}]`.

> Note that even though the `vocabulary` service exposes Drupal vocabulary terms and their URIs, the Linked Data Lookup field type in the consuming Drupal is not a taxonomy reference field, it is a structured field comprised of two text subfields, one for the label and the other for the URI. In other words, the source Drupal manages the Linked Data vocabulary as a standard Drupal vocabulary (with a URI field added) but the consuming Drupal stores the exposed Linked Data as pairs of labels and URIs.

* The endpoint for the "node" service is `/autocomplete_endpoint/node?content_type=my_content_type&uri_fields=field_uri&q=`
   * The "content_type" parameter is the machine name of the content type of the nodes you want to expose.
   * The "uri_fields" parameter is a comma-separated list of field on the content type that contain URIs. These fields should have a maximum of 1 value (i.e., not bw multivalued).
   * The "q" parameter is popluated by the Linked Data Lookup Field module and should be at the end of the URL.
   * For example, using nodes of content type `my_content_type` with a field `field_uri`, entering "d" in the autocomplete field configured to use this endpoint will produce the results `[{"label":"Dogs","uri":"http:\/\/example.com\/dogs"},{"label":"Donuts","uri":"http:\/\/example.com\/donuts"}]`.

* The endpoint for the "sample" service is `/autocomplete_endpoint/sample?q=`
   * For example, entering "f" in the autocomplete field configured to use this endoint will produce the results `[{"label":"four","uri":"http:\/\/example.com\/four"},{"label":"five","uri":"http:\/\/example.com\/five"},{"label":"fifteen","uri":"http:\/\/example.com\/fifteen"}]`.

### On the Drupal instance running Linked Data Lookup Field (the "consumer")

To add a new endpoint field to a content type that consumes a shared Linked data vocabulary, do the following:

* Ask the administrator of the provider Drupal instance what URL to to use in the "Base URL" field as described below.
* Go to Admin > Structure > Linked Data Lookup Endpoint > Add Linked Data Lookup Endpoint.
* Label: up to you.
* Endpoint type: `URL Argument Type`
* Base URL
   * If you are using the `vocabulary` service: [your Drupal's base URL]`/autocomplete_endpoint/vocabulary?vid=islandora_models&uri_fields=field_external_uri&q=` (`vid` and `uri_fields` values will vary depending on which vocabulary is being exposed; `q=` should be at the end). 
   * If you are using the `node` service: [your Drupal's base URL]`/autocomplete_endpoint/node?content_type=my_content_type&uri_fields=field_uri&q=` (`content_type` and `uri_fields` values will vary depending on which content type is being exposed; `q=` should be at the end).
   * Reminder: you won't be able to guess at these values, you will need to get the exact URL to use from the administrator of the provider Drupal instance.
* Result record JSON path: `[*]`
* Label JSON key: `label`
* URL JSON key: `uri`

Your endpoint is now configured as a field that can be added to a content type. To add it:

1. Go to Admin > Structure > Content types > [your content type] > Manage fields > Add field
1. Choose "Linked Data Lookup Field" as the field type
1. Choose the new endpoint you created following the instructions above.

## Current maintainer

[Mark Jordan](https://github.com/mjordan)

## License

[GPLv2](http://www.gnu.org/licenses/gpl-2.0.txt)
