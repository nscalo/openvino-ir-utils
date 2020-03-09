## IR Model Parser

In order to obtain the model outputs and inputs configured into the model optimizer command line, the output of the initial IR model needs to be parsed initially. 

An alternative is to rely on UI based applications that can automate the process, here I would like to present a simple elegant `php` command line application that outputs the input layers, output layers and all shapes required or amended by the command line application.

### To execute in command line

In order to execute in command line, run:

> cd Model-Parser/

> php -f IRModelParser.php sample_model.xml

## COCO Dataset annotations, a data transformation task

In this exercise, the MS COCO dataset keypoints are transformed to a generic human pose keypoints represented in xml format. Each json and xml file gets passed to the OpenVINO's annotation accuracy and convert annotation tool. 

### To execute in command line

In order to execute in command line, run:

> cd COCO-Dataset/annotations/

> composer install 

> php -f COCOKeypointsAnnotationConverter.php 2017

