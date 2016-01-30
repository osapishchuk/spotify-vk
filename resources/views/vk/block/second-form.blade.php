<div id="form_wizard_1" class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            </i>  VK Wizard -   <span class="step-title">Step 2 of 3</span>
        </div>
    </div>
    <div class="portlet-body form">
        <form id="submit_form" class="form-horizontal" action="#" novalidate="novalidate">
            <div class="form-wizard">
                <div class="navbar steps">
                    <div class="navbar-inner">
                        <ul class="row-fluid nav nav-pills">
                            <li class="span4 done">
                                <a class="step active" href="javascript:void(0);">
                                    <span class="number">1</span>
                                    <span class="desc"><i class="icon-ok"></i> Login/Approve</span>
                                </a>
                            </li>
                            <li class="span4 active">
                                <a class="step active" href="javascript:void(0);">
                                    <span class="number">2</span>
                                    <span class="desc"><i class="icon-ok"></i> Approve</span>
                                </a>
                            </li>
                            <li class="span4">
                                <a class="step active" href="javascript:void(0);">
                                    <span class="number">3</span>
                                    <span class="desc"><i class="icon-ok"></i> Search/Import</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="progress progress-info progress-striped" id="bar">
                    <div class="bar" style="width: 45%;"></div>
                </div>
                <div class="form-actions clearfix">
                    <a class="btn blue button-next pull-right" href="{{URL::to('/vk/step_three')}}">
                        Continue <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>