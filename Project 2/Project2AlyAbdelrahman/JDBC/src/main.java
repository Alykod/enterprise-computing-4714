import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Vector;

import com.mysql.jdbc.MySQLConnection;

/**
 * Aly abdelrahman
 * CNT 4714
 * Project 2 Summer 2016
 *
 */
public class main {

	private MySQLConnection mConnection;
	private ResultSetMetaData mMetaData;
	private Vector<String> mColumns;
	
	
	public main(MySQLConnection Connection){
		this.mConnection = Connection;
	}
	
	
	public Vector<Vector<String>> runQuery(String mQuery) throws SQLException{
		Vector<Vector<String>> mResults = new Vector<Vector<String>>();
		Statement mStatement = (Statement) this.mConnection.createStatement();
		ResultSet mResultSet = mStatement.executeQuery(mQuery);
		mMetaData = mResultSet.getMetaData();
		
		int mNumColumns = mMetaData.getColumnCount();
		setColumns(mNumColumns,mMetaData);
		
		while(mResultSet.next()){
			Vector<String> mRow = new Vector<String>();
			for(int i = 1; i <= mNumColumns; i++){
				mRow.add(mResultSet.getString(i));
			}
			mResults.add(mRow);
		}
		return mResults;
	}
	
	
	public Vector<String> getColumns() throws SQLException{
		return this.mColumns;
	}
	
	
	public int runUpdate(String mQuery) throws SQLException{
		Statement mStatement = this.mConnection.createStatement();
		return mStatement.executeUpdate(mQuery);
	}
	
	
	public void setColumns(int mNumColumns, ResultSetMetaData mMetaData) throws SQLException{
		mColumns = new Vector<String>();
		for(int i = 1; i <= mNumColumns; i++){
			mColumns.add(mMetaData.getColumnName(i));
		}
	}
}
