/*
    Aly Abdelrahman
	CNT 4714
*/

import java.sql.*;

public class ResultHelper {

    private ResultSet data;
    private ResultSetMetaData metadata;

    public ResultHelper(ResultSet rs) throws SQLException {
        data = rs;
        metadata = rs.getMetaData();
    }

    public String generateTable() throws SQLException {
        String msg;
        String head;
        String body;

        int numCol = metadata.getColumnCount();

        head = "<thead><tr>";
        for (int i = 1; i < numCol+1; i++) {

            head += "<th>" + metadata.getColumnName(i) + "</th>";
        }
        head += "</tr></thead>";

        body = "<tbody>";
        while (data.next()) {
            body += "<tr>";
            for (int i = 1; i < numCol+1; i++ ) {
                body += "<td>" + data.getString(i) + "</td>";
            }
            body += "</tr>";
        }
        body += "</tbody>";
        msg = head+body;

        return msg;
    }
}
