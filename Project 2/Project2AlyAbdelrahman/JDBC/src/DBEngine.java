import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

import com.mysql.jdbc.MySQLConnection;

/**
 * Aly abdelrahman
 * CNT 4714
 * Project 2 Summer 2016
 *
 */
public class DBEngine  {
	private String mURL;
	private String mUser;
	private String mPassword;
	private MySQLConnection mConnection;
	
	

	public DBEngine(String URL, String Username, String Password){
		this.mURL = URL;
		this.mUser = Username;
		this.mPassword = Password;
	}
	

	public void EstablishConnection() throws SQLException{
		mConnection = (MySQLConnection) DriverManager.getConnection(this.mURL, this.mUser, this.mPassword);
	}
	
	
	public void CloseConnection() throws SQLException{
		mConnection.close();
	}
	
	public MySQLConnection getConnection(){
		return this.mConnection;
	}

}
