
(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href=".html">Eloquent</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href=".html">Typhax</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Eloquent_Typhax_Comparator" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Eloquent/Typhax/Comparator.html">Comparator</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Eloquent_Typhax_Comparator_TypeEquivalenceComparator" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Comparator/TypeEquivalenceComparator.html">TypeEquivalenceComparator</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Eloquent_Typhax_Parser" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Eloquent/Typhax/Parser.html">Parser</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Eloquent_Typhax_Parser_TypeParser" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Parser/TypeParser.html">TypeParser</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Eloquent_Typhax_Renderer" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Eloquent/Typhax/Renderer.html">Renderer</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Eloquent_Typhax_Renderer_CondensedTypeRenderer" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Renderer/CondensedTypeRenderer.html">CondensedTypeRenderer</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Renderer_TypeRenderer" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Renderer/TypeRenderer.html">TypeRenderer</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Eloquent_Typhax_Type" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Eloquent/Typhax/Type.html">Type</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Eloquent_Typhax_Type_AndType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/AndType.html">AndType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_ArrayType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/ArrayType.html">ArrayType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_BooleanType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/BooleanType.html">BooleanType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_CallableType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/CallableType.html">CallableType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_ExtensionType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/ExtensionType.html">ExtensionType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_FloatType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/FloatType.html">FloatType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_IntegerType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/IntegerType.html">IntegerType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_MixedType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/MixedType.html">MixedType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_NullType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/NullType.html">NullType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_NumericType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/NumericType.html">NumericType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_ObjectType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/ObjectType.html">ObjectType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_OrType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/OrType.html">OrType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_ResourceType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/ResourceType.html">ResourceType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_StreamType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/StreamType.html">StreamType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_StringType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/StringType.html">StringType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_StringableType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/StringableType.html">StringableType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_TraversablePrimaryType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/TraversablePrimaryType.html">TraversablePrimaryType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_TraversableType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/TraversableType.html">TraversableType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_TupleType" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/TupleType.html">TupleType</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_Type" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/Type.html">Type</a>                    </div>                </li>                            <li data-name="class:Eloquent_Typhax_Type_TypeVisitor" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Eloquent/Typhax/Type/TypeVisitor.html">TypeVisitor</a>                    </div>                </li>                </ul></div>                </li>                </ul></div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "Eloquent.html", "name": "Eloquent", "doc": "Namespace Eloquent"},{"type": "Namespace", "link": "Eloquent/Typhax.html", "name": "Eloquent\\Typhax", "doc": "Namespace Eloquent\\Typhax"},{"type": "Namespace", "link": "Eloquent/Typhax/Comparator.html", "name": "Eloquent\\Typhax\\Comparator", "doc": "Namespace Eloquent\\Typhax\\Comparator"},{"type": "Namespace", "link": "Eloquent/Typhax/Parser.html", "name": "Eloquent\\Typhax\\Parser", "doc": "Namespace Eloquent\\Typhax\\Parser"},{"type": "Namespace", "link": "Eloquent/Typhax/Renderer.html", "name": "Eloquent\\Typhax\\Renderer", "doc": "Namespace Eloquent\\Typhax\\Renderer"},{"type": "Namespace", "link": "Eloquent/Typhax/Type.html", "name": "Eloquent\\Typhax\\Type", "doc": "Namespace Eloquent\\Typhax\\Type"},
            {"type": "Interface", "fromName": "Eloquent\\Typhax\\Renderer", "fromLink": "Eloquent/Typhax/Renderer.html", "link": "Eloquent/Typhax/Renderer/TypeRenderer.html", "name": "Eloquent\\Typhax\\Renderer\\TypeRenderer", "doc": "&quot;The interface implemented by type renderers.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Renderer\\TypeRenderer", "fromLink": "Eloquent/Typhax/Renderer/TypeRenderer.html", "link": "Eloquent/Typhax/Renderer/TypeRenderer.html#method_render", "name": "Eloquent\\Typhax\\Renderer\\TypeRenderer::render", "doc": "&quot;Render the supplied type.&quot;"},
            
            {"type": "Interface", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/TraversablePrimaryType.html", "name": "Eloquent\\Typhax\\Type\\TraversablePrimaryType", "doc": "&quot;The interface used to identify traversable primary types.&quot;"},
                    
            {"type": "Interface", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/Type.html", "name": "Eloquent\\Typhax\\Type\\Type", "doc": "&quot;The interface implemented by types.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\Type", "fromLink": "Eloquent/Typhax/Type/Type.html", "link": "Eloquent/Typhax/Type/Type.html#method_accept", "name": "Eloquent\\Typhax\\Type\\Type::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Interface", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html", "name": "Eloquent\\Typhax\\Type\\TypeVisitor", "doc": "&quot;The interface implemented by type visitors.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitAndType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitAndType", "doc": "&quot;Visit an and type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitArrayType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitArrayType", "doc": "&quot;Visit an array type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitBooleanType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitBooleanType", "doc": "&quot;Visit a boolean type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitCallableType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitCallableType", "doc": "&quot;Visit a callable type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitExtensionType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitExtensionType", "doc": "&quot;Visit an extension type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitFloatType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitFloatType", "doc": "&quot;Visit a float type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitIntegerType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitIntegerType", "doc": "&quot;Visit an integer type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitMixedType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitMixedType", "doc": "&quot;Visit a mixed type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitNullType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitNullType", "doc": "&quot;Visit a null type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitNumericType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitNumericType", "doc": "&quot;Visit a numeric type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitObjectType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitObjectType", "doc": "&quot;Visit an object type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitOrType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitOrType", "doc": "&quot;Visit an or type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitResourceType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitResourceType", "doc": "&quot;Visit a resource type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitStreamType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitStreamType", "doc": "&quot;Visit a stream type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitStringType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitStringType", "doc": "&quot;Visit a string type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitStringableType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitStringableType", "doc": "&quot;Visit a stringable type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitTraversableType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitTraversableType", "doc": "&quot;Visit a traversable type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitTupleType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitTupleType", "doc": "&quot;Visit a tuple type.&quot;"},
            
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Comparator", "fromLink": "Eloquent/Typhax/Comparator.html", "link": "Eloquent/Typhax/Comparator/TypeEquivalenceComparator.html", "name": "Eloquent\\Typhax\\Comparator\\TypeEquivalenceComparator", "doc": "&quot;Compares types for equivalence.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Comparator\\TypeEquivalenceComparator", "fromLink": "Eloquent/Typhax/Comparator/TypeEquivalenceComparator.html", "link": "Eloquent/Typhax/Comparator/TypeEquivalenceComparator.html#method_create", "name": "Eloquent\\Typhax\\Comparator\\TypeEquivalenceComparator::create", "doc": "&quot;Create a new type equivalence comparator.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Comparator\\TypeEquivalenceComparator", "fromLink": "Eloquent/Typhax/Comparator/TypeEquivalenceComparator.html", "link": "Eloquent/Typhax/Comparator/TypeEquivalenceComparator.html#method_isEquivalent", "name": "Eloquent\\Typhax\\Comparator\\TypeEquivalenceComparator::isEquivalent", "doc": "&quot;Returns true if the supplied types are equivalent.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Parser", "fromLink": "Eloquent/Typhax/Parser.html", "link": "Eloquent/Typhax/Parser/TypeParser.html", "name": "Eloquent\\Typhax\\Parser\\TypeParser", "doc": "&quot;Parses Typhax type expressions.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Parser\\TypeParser", "fromLink": "Eloquent/Typhax/Parser/TypeParser.html", "link": "Eloquent/Typhax/Parser/TypeParser.html#method_create", "name": "Eloquent\\Typhax\\Parser\\TypeParser::create", "doc": "&quot;Create a new type parser.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Parser\\TypeParser", "fromLink": "Eloquent/Typhax/Parser/TypeParser.html", "link": "Eloquent/Typhax/Parser/TypeParser.html#method_parse", "name": "Eloquent\\Typhax\\Parser\\TypeParser::parse", "doc": "&quot;Parse the supplied source into a type.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Renderer", "fromLink": "Eloquent/Typhax/Renderer.html", "link": "Eloquent/Typhax/Renderer/CondensedTypeRenderer.html", "name": "Eloquent\\Typhax\\Renderer\\CondensedTypeRenderer", "doc": "&quot;Renders types using Typhax syntax, without whitespace.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Renderer\\CondensedTypeRenderer", "fromLink": "Eloquent/Typhax/Renderer/CondensedTypeRenderer.html", "link": "Eloquent/Typhax/Renderer/CondensedTypeRenderer.html#method_create", "name": "Eloquent\\Typhax\\Renderer\\CondensedTypeRenderer::create", "doc": "&quot;Create a new condensed type renderer.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Renderer\\CondensedTypeRenderer", "fromLink": "Eloquent/Typhax/Renderer/CondensedTypeRenderer.html", "link": "Eloquent/Typhax/Renderer/CondensedTypeRenderer.html#method_render", "name": "Eloquent\\Typhax\\Renderer\\CondensedTypeRenderer::render", "doc": "&quot;Render the supplied type.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Renderer", "fromLink": "Eloquent/Typhax/Renderer.html", "link": "Eloquent/Typhax/Renderer/TypeRenderer.html", "name": "Eloquent\\Typhax\\Renderer\\TypeRenderer", "doc": "&quot;The interface implemented by type renderers.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Renderer\\TypeRenderer", "fromLink": "Eloquent/Typhax/Renderer/TypeRenderer.html", "link": "Eloquent/Typhax/Renderer/TypeRenderer.html#method_render", "name": "Eloquent\\Typhax\\Renderer\\TypeRenderer::render", "doc": "&quot;Render the supplied type.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/AndType.html", "name": "Eloquent\\Typhax\\Type\\AndType", "doc": "&quot;Represents an and type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\AndType", "fromLink": "Eloquent/Typhax/Type/AndType.html", "link": "Eloquent/Typhax/Type/AndType.html#method_types", "name": "Eloquent\\Typhax\\Type\\AndType::types", "doc": "&quot;Get the sub-types.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\AndType", "fromLink": "Eloquent/Typhax/Type/AndType.html", "link": "Eloquent/Typhax/Type/AndType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\AndType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/ArrayType.html", "name": "Eloquent\\Typhax\\Type\\ArrayType", "doc": "&quot;Represents an array type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\ArrayType", "fromLink": "Eloquent/Typhax/Type/ArrayType.html", "link": "Eloquent/Typhax/Type/ArrayType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\ArrayType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/BooleanType.html", "name": "Eloquent\\Typhax\\Type\\BooleanType", "doc": "&quot;Represents a boolean type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\BooleanType", "fromLink": "Eloquent/Typhax/Type/BooleanType.html", "link": "Eloquent/Typhax/Type/BooleanType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\BooleanType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/CallableType.html", "name": "Eloquent\\Typhax\\Type\\CallableType", "doc": "&quot;Represents a callable type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\CallableType", "fromLink": "Eloquent/Typhax/Type/CallableType.html", "link": "Eloquent/Typhax/Type/CallableType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\CallableType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/ExtensionType.html", "name": "Eloquent\\Typhax\\Type\\ExtensionType", "doc": "&quot;Represents an extension type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\ExtensionType", "fromLink": "Eloquent/Typhax/Type/ExtensionType.html", "link": "Eloquent/Typhax/Type/ExtensionType.html#method_className", "name": "Eloquent\\Typhax\\Type\\ExtensionType::className", "doc": "&quot;Get the class name.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\ExtensionType", "fromLink": "Eloquent/Typhax/Type/ExtensionType.html", "link": "Eloquent/Typhax/Type/ExtensionType.html#method_types", "name": "Eloquent\\Typhax\\Type\\ExtensionType::types", "doc": "&quot;Get the sub-types.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\ExtensionType", "fromLink": "Eloquent/Typhax/Type/ExtensionType.html", "link": "Eloquent/Typhax/Type/ExtensionType.html#method_attributes", "name": "Eloquent\\Typhax\\Type\\ExtensionType::attributes", "doc": "&quot;Get the attributes.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\ExtensionType", "fromLink": "Eloquent/Typhax/Type/ExtensionType.html", "link": "Eloquent/Typhax/Type/ExtensionType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\ExtensionType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/FloatType.html", "name": "Eloquent\\Typhax\\Type\\FloatType", "doc": "&quot;Represents a float type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\FloatType", "fromLink": "Eloquent/Typhax/Type/FloatType.html", "link": "Eloquent/Typhax/Type/FloatType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\FloatType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/IntegerType.html", "name": "Eloquent\\Typhax\\Type\\IntegerType", "doc": "&quot;Represents an integer type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\IntegerType", "fromLink": "Eloquent/Typhax/Type/IntegerType.html", "link": "Eloquent/Typhax/Type/IntegerType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\IntegerType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/MixedType.html", "name": "Eloquent\\Typhax\\Type\\MixedType", "doc": "&quot;Represents a mixed type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\MixedType", "fromLink": "Eloquent/Typhax/Type/MixedType.html", "link": "Eloquent/Typhax/Type/MixedType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\MixedType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/NullType.html", "name": "Eloquent\\Typhax\\Type\\NullType", "doc": "&quot;Represents a null type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\NullType", "fromLink": "Eloquent/Typhax/Type/NullType.html", "link": "Eloquent/Typhax/Type/NullType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\NullType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/NumericType.html", "name": "Eloquent\\Typhax\\Type\\NumericType", "doc": "&quot;Represents a numeric type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\NumericType", "fromLink": "Eloquent/Typhax/Type/NumericType.html", "link": "Eloquent/Typhax/Type/NumericType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\NumericType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/ObjectType.html", "name": "Eloquent\\Typhax\\Type\\ObjectType", "doc": "&quot;Represents an object type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\ObjectType", "fromLink": "Eloquent/Typhax/Type/ObjectType.html", "link": "Eloquent/Typhax/Type/ObjectType.html#method_ofType", "name": "Eloquent\\Typhax\\Type\\ObjectType::ofType", "doc": "&quot;Get the of type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\ObjectType", "fromLink": "Eloquent/Typhax/Type/ObjectType.html", "link": "Eloquent/Typhax/Type/ObjectType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\ObjectType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/OrType.html", "name": "Eloquent\\Typhax\\Type\\OrType", "doc": "&quot;Represents an or type.&quot;"},
                    
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/ResourceType.html", "name": "Eloquent\\Typhax\\Type\\ResourceType", "doc": "&quot;Represents a resource type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\ResourceType", "fromLink": "Eloquent/Typhax/Type/ResourceType.html", "link": "Eloquent/Typhax/Type/ResourceType.html#method_ofType", "name": "Eloquent\\Typhax\\Type\\ResourceType::ofType", "doc": "&quot;Get the of type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\ResourceType", "fromLink": "Eloquent/Typhax/Type/ResourceType.html", "link": "Eloquent/Typhax/Type/ResourceType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\ResourceType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/StreamType.html", "name": "Eloquent\\Typhax\\Type\\StreamType", "doc": "&quot;Represents a stream type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\StreamType", "fromLink": "Eloquent/Typhax/Type/StreamType.html", "link": "Eloquent/Typhax/Type/StreamType.html#method_readable", "name": "Eloquent\\Typhax\\Type\\StreamType::readable", "doc": "&quot;Get the readable condition.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\StreamType", "fromLink": "Eloquent/Typhax/Type/StreamType.html", "link": "Eloquent/Typhax/Type/StreamType.html#method_writable", "name": "Eloquent\\Typhax\\Type\\StreamType::writable", "doc": "&quot;Get the writable condition.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\StreamType", "fromLink": "Eloquent/Typhax/Type/StreamType.html", "link": "Eloquent/Typhax/Type/StreamType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\StreamType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/StringType.html", "name": "Eloquent\\Typhax\\Type\\StringType", "doc": "&quot;Represents a string type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\StringType", "fromLink": "Eloquent/Typhax/Type/StringType.html", "link": "Eloquent/Typhax/Type/StringType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\StringType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/StringableType.html", "name": "Eloquent\\Typhax\\Type\\StringableType", "doc": "&quot;Represents a stringable type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\StringableType", "fromLink": "Eloquent/Typhax/Type/StringableType.html", "link": "Eloquent/Typhax/Type/StringableType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\StringableType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/TraversablePrimaryType.html", "name": "Eloquent\\Typhax\\Type\\TraversablePrimaryType", "doc": "&quot;The interface used to identify traversable primary types.&quot;"},
                    
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/TraversableType.html", "name": "Eloquent\\Typhax\\Type\\TraversableType", "doc": "&quot;Represents a traversable type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TraversableType", "fromLink": "Eloquent/Typhax/Type/TraversableType.html", "link": "Eloquent/Typhax/Type/TraversableType.html#method_primaryType", "name": "Eloquent\\Typhax\\Type\\TraversableType::primaryType", "doc": "&quot;Get the primary type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TraversableType", "fromLink": "Eloquent/Typhax/Type/TraversableType.html", "link": "Eloquent/Typhax/Type/TraversableType.html#method_keyType", "name": "Eloquent\\Typhax\\Type\\TraversableType::keyType", "doc": "&quot;Get the key type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TraversableType", "fromLink": "Eloquent/Typhax/Type/TraversableType.html", "link": "Eloquent/Typhax/Type/TraversableType.html#method_valueType", "name": "Eloquent\\Typhax\\Type\\TraversableType::valueType", "doc": "&quot;Get the value type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TraversableType", "fromLink": "Eloquent/Typhax/Type/TraversableType.html", "link": "Eloquent/Typhax/Type/TraversableType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\TraversableType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/TupleType.html", "name": "Eloquent\\Typhax\\Type\\TupleType", "doc": "&quot;Represents a tuple type.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TupleType", "fromLink": "Eloquent/Typhax/Type/TupleType.html", "link": "Eloquent/Typhax/Type/TupleType.html#method_types", "name": "Eloquent\\Typhax\\Type\\TupleType::types", "doc": "&quot;Get the sub-types.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TupleType", "fromLink": "Eloquent/Typhax/Type/TupleType.html", "link": "Eloquent/Typhax/Type/TupleType.html#method_accept", "name": "Eloquent\\Typhax\\Type\\TupleType::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/Type.html", "name": "Eloquent\\Typhax\\Type\\Type", "doc": "&quot;The interface implemented by types.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\Type", "fromLink": "Eloquent/Typhax/Type/Type.html", "link": "Eloquent/Typhax/Type/Type.html#method_accept", "name": "Eloquent\\Typhax\\Type\\Type::accept", "doc": "&quot;Accept a visitor.&quot;"},
            
            {"type": "Class", "fromName": "Eloquent\\Typhax\\Type", "fromLink": "Eloquent/Typhax/Type.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html", "name": "Eloquent\\Typhax\\Type\\TypeVisitor", "doc": "&quot;The interface implemented by type visitors.&quot;"},
                                                        {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitAndType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitAndType", "doc": "&quot;Visit an and type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitArrayType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitArrayType", "doc": "&quot;Visit an array type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitBooleanType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitBooleanType", "doc": "&quot;Visit a boolean type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitCallableType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitCallableType", "doc": "&quot;Visit a callable type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitExtensionType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitExtensionType", "doc": "&quot;Visit an extension type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitFloatType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitFloatType", "doc": "&quot;Visit a float type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitIntegerType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitIntegerType", "doc": "&quot;Visit an integer type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitMixedType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitMixedType", "doc": "&quot;Visit a mixed type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitNullType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitNullType", "doc": "&quot;Visit a null type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitNumericType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitNumericType", "doc": "&quot;Visit a numeric type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitObjectType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitObjectType", "doc": "&quot;Visit an object type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitOrType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitOrType", "doc": "&quot;Visit an or type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitResourceType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitResourceType", "doc": "&quot;Visit a resource type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitStreamType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitStreamType", "doc": "&quot;Visit a stream type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitStringType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitStringType", "doc": "&quot;Visit a string type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitStringableType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitStringableType", "doc": "&quot;Visit a stringable type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitTraversableType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitTraversableType", "doc": "&quot;Visit a traversable type.&quot;"},
                    {"type": "Method", "fromName": "Eloquent\\Typhax\\Type\\TypeVisitor", "fromLink": "Eloquent/Typhax/Type/TypeVisitor.html", "link": "Eloquent/Typhax/Type/TypeVisitor.html#method_visitTupleType", "name": "Eloquent\\Typhax\\Type\\TypeVisitor::visitTupleType", "doc": "&quot;Visit a tuple type.&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


