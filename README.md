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


> Note that even though a `vocabulary` endpoint configuration exposes Drupal vocabulary terms and their URIs, the Linked Data Lookup field type in the consuming Drupal is not a taxonomy reference field, it is a structured field comprised of two text subfields, one for the label and the other for the URI. In other words, the source Drupal manages the Linked Data vocabulary as a standard Drupal vocabulary (with a URI field added) but the consuming Drupal stores the exposed Linked Data as pairs of labels and URIs.

Vocabularies exposed in this way are not limited to use by other Drupal instances. The data that this module exposes is consistent with the data exposed by the [Library of Congress](http://id.loc.gov/) and other providers of Linked Data vocabularies.

## Requirements

There are no hard requirements for installing this module other than Drupal 8, but data it exposes was initially intended to be consumed by [Linked Data Lookup Field](https://drupal.org/project/linked_data_field).

This module is Drupal 9 ready.

## Installation

1. Clone this repo into your Islandora's `drupal/web/modules/contrib` directory.
1. Enable the module either under the "Admin > Extend" menu or by running `drush en -y autocomplete_endpoint`.

## Configuration

### Providing URIs for things

Before you create autocomplete endpoints, you will need to add a special field to the vocabulary or content type that you want to expose as linked data. This field is not really that special, but it must be present. You will need to identify this field when you create your endpoints as described below.

This field will hold the Linked Data URI for each vocabulary term or node. When you create it, choose "Text (plain)". It doesn't matter what you name it.

### Creating the autocomplete endpoints

Enpoints are configured at `/admin/autocomplete_endpoint`. Once they exist, they can be used as described in the "Usage" section below.

The configuration form asks for the "machine name" of various things. This is obtainable in the following ways:

* Vocabulary ID: when you are viewing the list of terms in a vocabulary, e.g., `/admin/structure/taxonomy/manage/genre/overview`, the vocabulary's machine name is the string that comes before "overview", in this example "genre".
* Node content type: when you are viewing the list of fields in a content type, e.g., `/admin/structure/types/manage/islandora_object/fields`, the content type's machine name is the string that comes before "fields", in this example "islandora_object".
* URI field: for both vocabularies and content types, when you are viewing the "Manage Fields" tab, the machine name of the field you have configured to store Linked Data URIs.

If you want the Autocomplete Endpoint module to generate Linked Data URIs for items, check the "Provide a default URI" box and a prefix. 

## Usage

### On the Drupal instance exposing the data (the "provider")

Once you have configured an endpoint, it is ready for consumers to use. You will need to provide to those sites' admins a base URL. This URL contains the "machine name" of the autocomplete endpoint configuration, which is displayed in the list of endpoints. The base URL will look like:

[your Drupal's hostname]`/autocomplete_endpoint/myendpointsmachinename?q=`

where `myendpointsmachinename` is the machine name of the autocomplete endpoint. Note that the `q` URL parameter is empty.

### On the Drupal instance running Linked Data Lookup Field (the "consumer")

To add a new endpoint field to a content type that consumes a shared Linked data vocabulary, do the following:

* Ask the administrator of the provider Drupal instance what URL to to use in the "Base URL" field as described below.
* Go to Admin > Structure > Linked Data Lookup Endpoint > Add Linked Data Lookup Endpoint.
* Label: up to you.
* Endpoint type: `URL Argument Type`
* Base URL, as described in the previous section
   * Reminder: you won't be able to guess at this value, you will need to get the exact URL to use from the administrator of the provider Drupal instance.
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
