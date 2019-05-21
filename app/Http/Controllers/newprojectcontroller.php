<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Project;

class newprojectcontroller extends Controller
{
    //
	public function create()
    {
        return view('project.newproject');
    }
	
	public function store(Request $request)
    {
      
      if(!empty($request->get('projecttype_checklist'))){
        var_dump($request->get('projecttype_checklist'));
        echo '<br>'; 
      } 
      
       if(!empty($request->get('epctype_checklist'))){
        var_dump($request->get('epctype_checklist'));
      }
      //$newproject = new Project();
        //$newproject->cegproposalauthor= $request->get('cegproposalauthor');
        //$newproject->projectname= $request->get('projectname');
        //$newproject->clientcontactname= $request->get('clientcontactname');
        //$newproject->clientcompany = $request->get('clientcompany');        
        //$newproject->mwsize = $request->get('mwsize');        
        //$newproject->voltage = $request->get('voltage');        
        //$newproject->dollarvalueinhouse = $request->get('dollarvalueinhouse');        
        //$newproject->dateproposed = $request->get('dateproposed');        
        //$newproject->datentp = $request->get('datentp');        
        //$newproject->dateenergization = $request->get('dateenergization');        

        //$newproject->projecttypewind = $request->get('projectrypewind');        
        //$newproject->projecttypesolar = $request->get('projectypesolar');        
        //$newproject->projecttypestorage = $request->get('projecttypestorage');        
        //$newproject->projecttypearray = $request->get('projecttypearray');        
        //$newproject->projecttypetransmission = $request->get('projecttypetransmission');        
        //$newproject->projecttypesubstation = $request->get('projecttypesubstation');        
        //$newproject->projecttypedistribution = $request->get('projecttypedistribution');        
        //$newproject->projecttypescada = $request->get('projectypescada');        
        //$newproject->projecttypestudy = $request->get('projectypestudy');        
	//
        //$newproject->electricalengineering = $request->get('electricalengineering');        
        //$newproject->civilengineering = $request->get('civilengineering');        
        //$newproject->structuralmechanicalengineering = $request->get('structuralmechanicalengineering');        
        //$newproject->procurement = $request->get('procurement');        
        //$newproject->construction = $request->get('construction');        

        //$newproject->sel1 = $request->get('sel1');        
        //$newproject->projectcode = $request->get('projectcode');        
        //$newproject->projectmanager = $request->get('projectmanager');        
        //$newproject->save();
        //return redirect('/projectindex')->with('success', 'newproject has been successfully added');
    }

    public function update(Request $request, $id)
    {
        $newproject = Project::find($id);
        $newproject->cegproposalauthor= $request->get('cegproposalauthor');
        $newproject->projectname= $request->get('projectname');
        $newproject->clientcontactname= $request->get('clientcontactname');
        $newproject->clientcompany = $request->get('clientcompany');        
        $newproject->mwsize = $request->get('mwsize');        
        $newproject->voltage = $request->get('voltage');        
        $newproject->dollarvalueinhouse = $request->get('dollarvalueinhouse');        
        $newproject->dateproposed = $request->get('dateproposed');        
        $newproject->datentp = $request->get('datentp');        
        $newproject->dateenergization = $request->get('dateenergization');        

        $newproject->projecttypewind = $request->get('projectrypewind');        
        $newproject->projecttypesolar = $request->get('projectypesolar');        
        $newproject->projecttypestorage = $request->get('projecttypestorage');        
        $newproject->projecttypearray = $request->get('projecttypearray');        
        $newproject->projecttypetransmission = $request->get('projecttypetransmission');        
        $newproject->projecttypesubstation = $request->get('projecttypesubstation');        
        $newproject->projecttypedistribution = $request->get('projecttypedistribution');        
        $newproject->projecttypescada = $request->get('projectypescada');        
        $newproject->projecttypestudy = $request->get('projectypestudy');        
    
        $newproject->electricalengineering = $request->get('electricalengineering');        
        $newproject->civilengineering = $request->get('civilengineering');        
        $newproject->structuralmechanicalengineering = $request->get('structuralmechanicalengineering');        
        $newproject->procurement = $request->get('procurement');        
        $newproject->construction = $request->get('construction');        

        $newproject->sel1 = $request->get('sel1');        
        $newproject->projectcode = $request->get('projectcode');        
        $newproject->projectmanager = $request->get('projectmanager');        
        $newproject->save();
        return redirect('/projectindex')->with('success', 'newproject has been successfully added');
    }




    public function index()
    {
        $projects=Project::all();
        return view('/projectindex',compact('projects'));
    }

    public function edit($id)   
    {
        $newproject = Project::find($id);
        return view('/editproject/{id}',compact('newproject','id'));
    }

    public function destroy($id)
    {
        $newproject = Project::find($id);
        $newproject->delete();
        return redirect('/projectindex')->with('success','Newproject has been  deleted');
    }
	
}

