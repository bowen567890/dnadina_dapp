<script src="/js/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/gojs@2.3.14/release/go.js"></script>
<script src="/js/bootstrap.bundle.min.js" ></script>

<form class="row g-3">
    <div class="col-auto">
        <input type="text" id="input1" class="form-control" value="{{$parentAddress}}" placeholder="请输入用户地址">
    </div>
    <div class="col-auto">
        <button type="button" class="btn btn-primary mb-3" id="btn1">当前地址</button>
    </div>
    <div class="col-auto">
        <button type="button" class="btn btn-primary mb-3" id="btn2">当前地址上级</button>
    </div>
    <div class="col-auto">
        <button type="button" class="btn btn-primary mb-3" id="btn3">回到顶级</button>
    </div>
</form>
<div id="myDiagramDiv" style="width:100%; height:700px; border:1px solid black;">

</div>

<script>
    $(document).ready(function() {
        $('#btn1').on( "click", function( event ) {
            window.location.href = '{{admin_url('newUserTree')}}'+ '?parent_address=' + $("#input1").val();
        });
        $('#btn2').on( "click", function( event ) {
            window.location.href = '{{admin_url('newUserTree')}}'+ '?is_previous=1&parent_address=' + $("#input1").val();
        });
        $('#btn3').on( "click", function( event ) {
            window.location.href = '{{admin_url('newUserTree')}}';
        });
    })
    function init() {
        var $ = go.GraphObject.make;  // 定义一个方便的别名

        var myDiagram =
            $(go.Diagram, "myDiagramDiv",
                {
                    "undoManager.isEnabled": true,
                    layout: $(go.TreeLayout, { angle: 90, layerSpacing: 35 }),
                    allowZoom: true,  // 允许缩放
                    minScale: 0.1,    // 最小缩放比例
                    maxScale: 5,      // 最大缩放比例
                    allowHorizontalScroll: true,  // 允许水平滚动
                    allowVerticalScroll: true,  // 允许垂直滚动
                    "toolManager.mouseWheelBehavior": go.ToolManager.WheelZoom,  // 使用鼠标滚轮缩放
                    "animationManager.isEnabled": false,  // 禁用动画以提高性能（可选）
                    contentAlignment: go.Spot.Center,  // 内容居中对齐
                    isReadOnly: false,  // 允许编辑
                    "grid.visible": true,  // 显示网格（可选）
                    "grid.gridCellSize": new go.Size(10, 10),  // 网格大小
                    scrollMode: go.Diagram.InfiniteScroll  // 无限滚动模式
                });

        // 定义节点模板
        myDiagram.nodeTemplate =
            $(go.Node, "Auto",
                {
                    click: function(e, obj) {
                        var node = obj.part;
                        if (node instanceof go.Node) {
                            var diagram = node.diagram;
                            diagram.startTransaction("CollapseExpandTree");

                            // 如果节点已经加载过子节点，不再请求后端数据
                            if (node.data._childrenLoaded) {
                                toggleVisibility(node, !node.data._expanded);
                                node.data._expanded = !node.data._expanded;
                            } else {
                                // 请求后端数据获取子节点
                                fetch('{{admin_url('getChildrenUser').'?parentKey=' }}'+ node.data.key)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data && data.length > 0) {
                                            // 添加子节点数据到模型中
                                            myDiagram.model.addNodeDataCollection(data);
                                            node.data._childrenLoaded = true; // 设置标志位，标明已经加载过子节点
                                            node.data._expanded = true; // 默认展开子节点
                                            toggleVisibility(node, true);
                                        } else {
                                            node.data._childrenLoaded = true; // 即使没有子节点数据也设置标志位，避免重复请求
                                            node.data._expanded = false;
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error fetching child nodes:', error);
                                    });
                            }

                            diagram.commitTransaction("CollapseExpandTree");
                        }
                    }
                },
                // 卡片背景和美化（去掉 shadow）
                $(go.Shape, "RoundedRectangle",
                    {
                        fill: "#5cb85c",
                        strokeWidth: 2,
                        parameter1: 16 // 圆角半径
                    }
                ),
                $(go.Panel, "Table",
                    { margin: 16, maxSize: new go.Size(240, NaN) },
                    // 昵称加粗
                    $(go.TextBlock,
                        { row: 0, column: 0, columnSpan: 2, font: "bold 18px 'Segoe UI', sans-serif", stroke: "#ffffff", margin: new go.Margin(0,0,8,0) },
                        new go.Binding("text", "address")),
                    // UID
                    $(go.TextBlock, "UID: ", { row: 1, column: 0, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" }),
                    $(go.TextBlock, { row: 1, column: 1, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" },
                        new go.Binding("text", "key")),
                    // 直推人数
                    $(go.TextBlock, "直推人数: ", { row: 3, column: 0, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" }),
                    $(go.TextBlock, { row: 3, column: 1, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" },
                        new go.Binding("text", "zhi_num")),
                    // 团队人数
                    $(go.TextBlock, "团队人数: ", { row: 4, column: 0, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" }),
                    $(go.TextBlock, { row: 4, column: 1, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" },
                        new go.Binding("text", "team_num")),
                    // 个人业绩
                    $(go.TextBlock, "个人业绩: ", { row: 5, column: 0, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" }),
                    $(go.TextBlock, { row: 5, column: 1, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" },
                        new go.Binding("text", "me_performance")),
                    // 团队业绩
                    $(go.TextBlock, "团队业绩: ", { row: 6, column: 0, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" }),
                    $(go.TextBlock, { row: 6, column: 1, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" },
                        new go.Binding("text", "team_performance")),
                    // 注册日期
                    $(go.TextBlock, "注册日期: ", { row: 7, column: 0, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" }),
                    $(go.TextBlock, { row: 7, column: 1, font: "15px 'Segoe UI', sans-serif", stroke: "#ffffff" },
                        new go.Binding("text", "created_at"))
                )
            );

        // 定义链接模板
        myDiagram.linkTemplate =
            $(go.Link, go.Link.Orthogonal,
                { corner: 5, relinkableFrom: true, relinkableTo: true },
                $(go.Shape, { strokeWidth: 3, stroke: "#555" }));

        // 定义数据模型
        var model = $(go.TreeModel);
        model.nodeDataArray = @json($firstAddress);
        myDiagram.model = model;

        // 定义递归函数来切换子节点及其所有子节点的可见性
        function toggleVisibility(node, shouldShow) {
            var childNodes = node.findTreeChildrenNodes();
            while (childNodes.next()) {
                var child = childNodes.value;
                child.visible = shouldShow;
                if (child.visible) {
                    toggleVisibility(child, shouldShow);
                }
            }
        }
    }

    window.addEventListener('DOMContentLoaded', init);
</script>
