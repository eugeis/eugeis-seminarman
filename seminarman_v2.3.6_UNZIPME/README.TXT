OSG Seminar Manager Installation
================================

Install the component and the plugin using the extension manager.
If you want to generate invoices, the following directories must be
writeable by the webserver:

  administrator/components/com_seminarman/classes/tcpdf/cache
  invoices (pdf files will be stored here by default)

For better security you can also use a different directory for invoices,
e.g. outside your DocumentRoot. If you do so please make sure it is
writeable by the webserver and specify the directory in the options
dialog of the component.

 
IMPORTANT NOTES to the Upgrade from 1.0/1.0.x to 2.2.0
===============================

There are no special actions required. Some DB Fields will be added,
see admin/sql/updates, but this happens automatically.

!!! IMPORTANT NOTES regarding the Update to V2.2.0 !!!

The content of the field description in trainer details are now shown in the frontend!
If you do not want to show the description to the public note how to inactivate the Tutor Profile in the Frontend:

There are two ways to reach the tutor profile in the frontend:
Fom the menu 'Trainer Overview' and from the link (tutor name) in the course details.
If you do not want to have the content of the tutor's description field visible, you have the following possibilities:

1. When creating a course, choose 'Parameters Advanced > Show Tutor > Hide'. This way, the tutor name is not shown in the course details in the frontend, so that there is no link to the tutor profile. If you additionally have created a menu item 'Trainer Overview', the tutor profile is still available using this link.
2. If you want the tutor name to show up, but without a link to the tutor view: Choose 'Parameters Advanced > Show Tutor > Show' in the backend. In the tutor list, uncheck the tutor at 'published'. This way, the tutor will not show up any more in the tutor list in the frontend at the menu item 'tutor list'.
