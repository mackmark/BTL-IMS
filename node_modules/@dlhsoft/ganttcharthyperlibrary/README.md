# DlhSoft Gantt Chart Hyper Library

[Get Started](#getStarted) | [Components](#components) | [Features](#features) | [Demos](https://dlhsoft.com/GanttChartHyperLibrary/Demos) | [License](#license)

**[DlhSoft-Gantt Chart Hyper Library](https://dlhsoft.com/GanttChartHyperLibrary/)**, for **HTML5** and **JavaScript®**, with optional TypeScript support and **Angular**, **React** & **Vue extensions**, was built with **inline SVG support** and it includes a set of **interactive Gantt chart and scheduling components** (presenting hierarchical task and resource collections, dependencies between items, timelines with scaling from seconds to decades) and integrated **network diagram and PERT chart** add-ons as well, with built-in **project management features**, ready for the JavaScript framework of your choice.

They were designed to empower businesses worldwide and developed with **common customer requirements in mind**, and are used by **companies of all sizes, government organizations, and even educational institutions** to create stunning **Gantt chart applications** with more ease. Whether you or your end users need to **manage project schedules, track resources, or simply visualize timelines**, this library has got you covered.

<img src="https://dlhsoft.com/GanttChartHyperLibrary/Documentation/Screenshots/HTML/Showcase.Powerful.png?1" height=460 alt="Gantt Chart component" />

FREE & UNLIMITED TRIAL & SUPPORT PROVIDED BY DEVELOPERS • CUSTOM SERVICES UPON REQUEST

<a name="getStarted"></a>
## Get Started ##
Get and apply the software library to your project using one of the ways we have made available:

- Using npm:
  ~~~html
  npm i @dlhsoft/ganttcharthyperlibrary
  ~~~

- [Download (.zip)](https://dlhsoft.com/GanttChartHyperLibrary/Download.aspx)

Prepare the script files in your project folder, and add the necessary DlhSoft.* script references to the head section(s) of HTML page(s) in your JavaScript® based application:
~~~html
<head>
  …
  <script src="DlhSoft.ProjectData.GanttChart.HTML.Controls.js" type="text/javascript"></script>
  <script src="DlhSoft.Data.HTML.Controls.js" type="text/javascript"></script>
  <script src="DlhSoft.ProjectData.GanttChart.HTML.Controls.Extras.js" type="text/javascript"></script>
</head>
~~~
Prepare control placeholder elements as child nodes of appropriate container elements and obtain their references in code behind:
~~~html
<body>
  <div id="ganttChartView">…</div>
</body>
~~~

### Data items ###

To load and present data items within GanttChartView or other Gantt Chart based component instances initialize their items collections. The item type to use depends on the actual component type. For Gantt Chart controls define objects providing these fields:
- content values usually specify task names;
- start and finish date and time values indicate the scheduled times of their displayed bars;
- completedFinish date and time values specify the schedule times of their completion displayed bars (indicating completion percentages);
- isMilestone Boolean values determine the project milestones that are presented as diamond shapes in the chart;
- assignmentsContent values specify indicate the assigned resource names of each task, separated by commas;
- indentation values generate the hierarchical work breakdown structure of the project (summary and standard tasks).
~~~javascript
var ganttChartView = document.querySelector('#ganttChartView');

var item1 = { content: 'My summary task' };
var item2 = { content: 'My standard task', indentation: 1,
              start: new Date(2023, 8, 2, 8, 0, 0), finish: new Date(2023, 8, 7, 16, 0, 0), completedFinish: new Date(2023, 8, 5, 16, 0, 0),
              assignmentsContent: 'My resource' };
var items = [ item1, item2 ];
var item3 = { content: 'My milestone', indentation: 1,
              start: new Date(2023, 8, 7, 16, 0, 0), isMilestone: true };
items.push(item3);
item3.predecessors = [{ item: item2, dependencyType: 'FF' }];

var settings = { currentTime: new Date(2023, 10, 2, 12, 0, 0) };
var columns = DlhSoft.Controls.GanttChartView.getDefaultColumns(items, settings);
columns[0].header = 'Work items';
columns[0].width  = 240;
columns[1].header = 'Beginning';
columns[2].header = 'Start';
columns.push({ header: 'Description', width: 200, cellTemplate: function (item) { 
    return item.ganttChartView.ownerDocument.createTextNode(item.description); } });
settings.columns = columns;

settings.timelineStart  = new Date(2023, 8, 2, 12, 0, 0); 
settings.timelineFinish = new Date(2023, 12, 2, 12, 0, 0); 
settings.hourWidth = 5; //Default is 5

DlhSoft.Controls.GanttChartView.initialize(ganttChartView, items, settings);
~~~
<a name="components"></a>
## Components ##

#### [GanttChartView](https://dlhsoft.com/GanttChartHyperLibrary/GanttChartView.html) ####
Hierarchical data grid and attached scheduling chart with drag and drop support and dependency lines.

<img src="https://dlhsoft.com/GanttChartHyperLibrary/Documentation/Screenshots/HTML/GanttChartView.MainFeatures.Generic-bright.Item.small.png?1" height=200/>

#### [ScheduleChartView](https://dlhsoft.com/GanttChartHyperLibrary/ScheduleChartView.html) ####
Scheduling chart that displays multiple bars on the same line with horizontal and vertical drag and drop support.

<img src="https://dlhsoft.com/GanttChartHyperLibrary/Documentation/Screenshots/HTML/ScheduleChartView.MainFeatures.Purple-green.small.png" height=200/>

#### [LoadChartView](https://dlhsoft.com/GanttChartHyperLibrary/LoadChartView.html) ####
Allocation chart displaying normal and over-allocations of one or more resources on a timeline with optional data grid and Gantt Chart integration.

<img src="https://dlhsoft.com/GanttChartHyperLibrary/Documentation/Screenshots/HTML/LoadChartView.SingleItem.Teal-green.small.png" height=120/>

#### [NetworkDiagramView](https://dlhsoft.com/GanttChartHyperLibrary/NetworkDiagramView.html) ####
Network diagram displaying task information and dependencies with drag and drop based rearranging support and optional Gantt Chart integration.

<img src="https://dlhsoft.com/GanttChartHyperLibrary/Documentation/Screenshots/HTML/NetworkDiagramView.MainFeatures.Generic-bright.png" height=300/>

#### [PertChartView](https://dlhsoft.com/GanttChartHyperLibrary/PertChartView.html) ####
PERT diagram displaying events and their dependencies with drag and drop based rearranging support and optional Gantt Chart integration.

<img src="https://dlhsoft.com/GanttChartHyperLibrary/Documentation/Screenshots/HTML/PertChartView.MainFeatures.Generic-bright.small.png" height=160/>

#### More ####
Miscellaneous controls to be used in conjunction with main components of the Gantt Chart library: month calendar (Calendar), date and time editors (DatePicker, TimePicker, DateTimePicker), and combo box with check boxes (MultiSelectorComboBox).

<a name="Features"></a>
## Features ##
- Editing tasks from grid, drag and drop bars, dependencies of all types;
-	Resources and allocations (people and material items);
-	Copy, paste, undo, redo (using separate, free, Undo Management Library from DlhSoft);
-	Project management features: baseline, auto-scheduling, critical item highlighting, resource levelling, baseline bars, task splitting;
-	High performance: user interface virtualization and asynchronous loading enabled by default, getting the components highly responsive;
-	Easy customization: grid columns, summarization, work breakdown structure, bars (colors, text labels, assigned resources as images or text, etc.), dependency lines, summary color backgrounds, nonworking or special day highlighting, built-in scale headers, date formats, zoom level, row and bar heights, etc.;
-	Loading and saving Microsoft Project XML content;
-	Printing, exporting images and support for generating Excel files (using separate Project Management Framework from DlhSoft, free for Gantt Chart Hyper Library licensees);
-	Support for mobile/touch-enabled devices: end users can drag task bars to reschedule them or update their completion values, and even create dependencies between tasks with their fingers, even on small screen gadgets; in the grid area they can further edit task names, dates, select assigned resources from a drop down list, and so on, directly from their phones, for example.
-	Chart headers/scales: the components come with configurable timeline duration and zoom level, and customizable intervals of visible week day and hour intervals; scale headers support multiple types of built-in and custom intervals and texts, date formatters, and you can even configure the update interval that would apply to round up times upon dragging task bars;
-	Specific grid cell editors;
-	SVG based bar extras defined through functions; 
- Task interruptions with custom coding;
- Task parts;
-	Non-working days/holidays;
-	Vertical drag and drop for tasks between different resource rows in Schedule Chart;
-	Vertically drag and drop tasks from Gantt Chart to Schedule Chart instances, with custom coding;
-	Show dependencies between tasks in Schedule Chart, too;
-	Load Chart, Pert Chart, Network Diagram: you can easily generate such diagrams based on Gantt Chart items (or by defining data on the fly).

<a name="license"></a>
## License ##
Gantt Chart Hyper Library is a commercial product. To obtain a development license please [order it from DlhSoft](https://dlhsoft.com/GCHL/purchase.aspx).

Licenses are perpetual, and only need to be renewed if you would like to download and use newer product builds released after one year since the original purchase.

Licensed components may be redistributed and/or hosted royalty-free embedded within your applications.

Source code is available for component libraries when purchasing Business Plus product licenses.

Regardless of legal guarantee limitations specified within the License Agreement documents of our products, we aim to provide free and unlimited  to all customers, including answering technical questions and resolving reported issues by providing updated builds and specific hot fixes, at the maximum possible responsiveness level, even while using the trial version of our software; note, though, that Business licensees have higher priority when submitting support requests.
