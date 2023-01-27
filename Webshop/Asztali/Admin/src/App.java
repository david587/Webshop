
import javafx.application.Application;
import javafx.scene.Scene;
import javafx.stage.Stage;
import views.MainView;

public class App extends Application {
    public static void main(String[] args) throws Exception {
        launch(args);
    }

    public void start(Stage stage){
        MainView mainView = new MainView();

        Scene scene = new Scene(mainView,700,500);
        stage.setScene(scene);
        stage.show();
    }
}    
