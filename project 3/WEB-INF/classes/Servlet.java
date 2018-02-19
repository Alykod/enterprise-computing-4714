/*
   Aly Abdelrahman
   CNT 4714 
*/

import javax.servlet.*;
import javax.servlet.http.*;
import java.io.*;
import java.sql.*;
import java.util.ArrayList;

public class Servlet extends HttpServlet {

    private Statement statement;
    private Connection connection;

    public void init(ServletConfig config) throws ServletException {
        try {
            Class.forName( config.getInitParameter("databaseDriver") );
            connection = DriverManager.getConnection(
                    config.getInitParameter("databaseName"),
                    config.getInitParameter("username"),
                    config.getInitParameter("1234")
            );
            statement = connection.createStatement();
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    protected void doPost( HttpServletRequest request,
                          HttpServletResponse response ) throws ServletException, IOException  {

        HttpSession session = request.getSession();
        String query = request.getParameter("query");
        ResultHelper helper;
        ResultSet data;
        ResultSet tempData;
        int updateData;
        int rows = 0;
        int count = 0;

        try {

            if (isSelect(query)) {
                data = statement.executeQuery(query);
                helper = new ResultHelper(data);
                session.setAttribute("msg", helper.generateTable());
                session.setAttribute("query", query);
                session.setAttribute("error", "");
                session.setAttribute("bizLogic", "");
                session.setAttribute("updatedRows", "");
            }
            
            else if (query.toLowerCase().contains("shipments")) {

                updateData = statement.executeUpdate(query);
                data = statement.executeQuery("select distinct snum from shipments where quantity >= 100");


                while(data.next()) {
                    count++;
                }

                if (count > 0) {
                    rows = bizLogic(data);
                    session.setAttribute("updatedRows", updateData);
                    session.setAttribute("bizLogic", rows);
                    session.setAttribute("query", query);
                    session.setAttribute("error", "");

                } else {

                    updateData = statement.executeUpdate(query);
                    session.setAttribute("updatedRows", updateData);
                    session.setAttribute("query", query);
                    session.setAttribute("error", "");
                    session.setAttribute("bizLogic", "");
                }


            }
            else {
                updateData = statement.executeUpdate(query);
                session.setAttribute("updatedRows", updateData);
                session.setAttribute("query", query);
                session.setAttribute("error", "");
                session.setAttribute("bizLogic", "");
            }


        } catch (SQLException e) {
            session.setAttribute("error", e.getMessage());
            session.setAttribute("query", query);
            e.printStackTrace();
        }

        RequestDispatcher rd = request.getRequestDispatcher("index.jsp");
        rd.forward(request,response);
    }

    public void Destroy() {
        try {
            statement.close();
            connection.close();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public boolean isSelect(String query) {
        String[] split = query.split(" ");
        if (split[0].equalsIgnoreCase("select")) {
            return true;
        }
        return false;
    }

    public int bizLogic(ResultSet data) throws SQLException {

        ArrayList<String> snum = new ArrayList<>();
        int rows = 0;
        data.beforeFirst();

        while(data.next()) {
            snum.add(data.getString("snum"));
        }


        for (int i = 0; i < snum.size(); i++) {
            rows += statement.executeUpdate("update suppliers set `status` = `status` + 5 where snum = '"+snum.get(i)+"';");
        }

        return rows;
    }
}
