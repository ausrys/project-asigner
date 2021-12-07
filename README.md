# project-asigner
For this task I have created 4 tables. Project table contains all the projects and their information. Grout table contains all the groups with the foreign id of project name. This in this table we can track which groups belongs to a certain project.  In student table we have 3 foreign keys - name, group_id, student_id. In this table we track to which project and group student belong. This was done because the task didn't mention, that a student cannot belong to another project, so a student can belong in another project but in the same group name. That is why this table is needed to track to which project and group student belongs to. Student list table contains the list of all the students. 

I created API end points -  create, get_single, read. To organise the code better, delete and update end points could be created too.


A student cannot be added twice in to the project. A student cannot be added in to another group in the same project. In the backend we get the array of students that belong in the groups. With this array we check how many students are in the certain group and if it isn't over students per group number. If it is, a message is printed that the group is full and the student is not added. 


What could be done to improve the system: organise the code so that it could be readable. Create delete and update endpoints. Create more utility functions. Rewrite some of the code using OOP. 

I have used a little of OOP because of my lack of knowledge and experience with OOP. 