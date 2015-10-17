<?php if(!defined('KIRBY')) exit ?>

title: Episode
pages: true
files: true
fields:
  title:
    label: Title
    type:  text
  interpret:
    label: Komponist / Esemble / Dirigent
    type:  text
    width: 1/3
  track:
    label: Name der Aufnahme
    type:  text
    width: 1/3
  date:
    label: Datum
    type:  date
    width: 1/3
  text:
    label: Beschreinung / Redaktioneller Text
    type:  textarea
  followup:
    label: Follow-Up Links
    type: structure
    entry: >
      <strong>{{title}}</strong> ({{url}})
    fields:
      title:
        label: Name
        type: text
      url:
        label: URL
        type: text