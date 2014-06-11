<?php

class feedbackControl extends control
{
	public function run()
	{
                $this->replaceView('title', 'Feedback');
                
                if(!$this->logged_in)
                {
                        header('Location: home.php');
                }
                
                
                $voorwerp = $this->feedbackModel->getKoperVerkoperGebruiker(round($this->get['voorwerp']));
                
                
                if(empty($voorwerp))
                {
                        $this->loadView('feedback/geenveiling', 'content');
                }
                else
                {
                        $feedback = $this->feedbackModel->getFeedbackGebruiker(round($this->get['voorwerp']));
                        if(!empty($feedback))
                        {
                                $this->loadView('feedback/alfeedback', 'content');
                        }
                        else
                        {
                                if(!empty($this->post['submit']))
                                {
                                        
                                }
                                else
                                {
                                        $this->loadView('feedback/feedback', 'content');
                                }
                        }
                }
                
	}
}

?>