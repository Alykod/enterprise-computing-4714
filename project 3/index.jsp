<!DOCTYPE html>
<html>
  <head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Project3</title>
   <link rel="stylesheet" href="bootstrap.min.css">
  </head>
  <body>

    <div class="container">

      <div class="row">
        <div class="col-md-12">
          <h1>Welcome to the Project 3</h1>
        </div>
      </div>

      <hr>

      <div class="row">
        <div class="col-md-12 text-center">

          <p>
            You are connected to the project3 database. Please enter any valild SQL query or update stetment.
            If no query/update command is given the Execute button will display all supplier information in the database.
            All execution results will appear below.
          </p>

          <form class="" action="/project3/project3servlet" method="post">
            <div class="form-group">
              <textarea name="query" name="query" rows="8" cols="100" id="query" class="form-control"><%= query %></textarea>
            </div>
            <div class="form-group">
              <input type="submit" name="execute" value="Execute Command" class="btn btn-primary">
              <input type="submit" name="clear" value="Clear Form" class="btn btn-primary">
            </div>
          </form>

        </div>
      </div>

      <hr>

      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <h3 class="text-center">Database Results:</h3>
          <% if (!error.isEmpty()) { %>
            <div class="bg-danger">
                <h5>Error executing the SQL statement:</h5>
                <p><%= error %></p>
            </div>
          <% } else { %>

            <% if (!businesslogic.isEmpty()) { %>
                <div class="bg-success">
                    <h5>The statement executed successfully. <%= updatedRows %> row(s) affected.</h5>
                    <p>Business Logic Detected! - Updating Supplier Status</p>
                    <p>Business Logic updated <%= businesslogic %> suppliers status marks.</p>
                </div>
            <% } else if (!updatedRows.isEmpty()) { %>
                <div class="bg-success">
                    <h5>The statement executed successfully. <%= updatedRows %> row(s) affected.</h5>
                </div>
            <% } else {%>
                <div class="table-responsive">
                    <table class="table">
                        <%= msg %>
                    </table>
                </div>
            <% } %>

          <% } %>

        </div>
      </div>

    </div>

  </body>
</html>
